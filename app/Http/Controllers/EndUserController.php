<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Cache;


class EndUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();
        $query->where('role', 'user');
        if ($request->filled('global')) {
            $search = $request->global;
            $query->where(function ($q) use ($search) {
                $q->where('id', $search)
                    ->orwhere('name', 'LIKE', "%{$search}%")
                    ->orwhere('email', 'LIKE', "%{$search}%")
                    ->orwhere('contact', 'LIKE', "%{$search}%")
                    ->orwhere('role', 'LIKE', "%{$search}%")
                ;
            });
        }


        if ($request->filled('sortBy')) {
            $direction = $request->sortBy === 'true' ? "desc" : "asc";
            $query->orderBy($request->sortBy, $direction);
        } else {
            $query->latest('created_at');
        }

        $perPage = $request->perPage ?? 10;
        $page = $request->page ?? 1;
        $endUsers = $query->paginate($perPage, ['*'], 'page', $page)
            ->appends($request->all());
        return Inertia::render(
            "Dashboard/End-User/Index",
            ['endUsers' => $endUsers]
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Dashboard/End-User/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|alpha_num|unique:users,username|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'contact' => 'required|string|min:10|max:20',
            'password' => 'required|string|min:8|confirmed',
            'avatar' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }



        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'role' => 'user',
            'contact' => $request->contact,
            'password' => bcrypt($request->password),
            'profile_photo_path' => $avatarPath,
        ]);
        return redirect()->route('end-users.index')->with("success", "End Users Created Successfully.");
    }

    /**
     * Display the specified resource.
     */
    public function show(User $end_user, Request $request)
    {
        // Cache key for this user's data
        $userCacheKey = "user_{$end_user->id}_data";

        // Get counts for stats cards with caching
        $counts = Cache::remember("{$userCacheKey}_counts", 3600, function () use ($end_user) {
            return [
                'applications' => $end_user->applications()->count(),
                'hiringRequests' => $end_user->hiringRequests()->count(),
                'transactions' => $end_user->transactions()->count(),
                'bookmarks' => $end_user->bookmarks()->count()
            ];
        });

        $data = [
            'user' => $end_user,
            'counts' => $counts
        ];

        // Load data based on the requested tab or default to applications
        $tab = $request->tab ?? 'applications';
        $tabCacheKey = "{$userCacheKey}_{$tab}";

        switch ($tab) {
            case 'applications':
                $data['applications'] = Cache::remember($tabCacheKey, 3600, function () use ($end_user) {
                    return $end_user->applications()
                        ->with(['property'])
                        ->latest()
                        ->paginate(10);
                });
                break;
            case 'hiring-requests':
                $data['hiringRequests'] = Cache::remember($tabCacheKey, 3600, function () use ($end_user) {
                    return $end_user->hiringRequests()
                        ->latest()
                        ->paginate(10);
                });
                break;
            case 'transactions':
                $data['transactions'] = Cache::remember($tabCacheKey, 3600, function () use ($end_user) {
                    return $end_user->transactions()
                        ->with(['property', 'payment'])
                        ->latest()
                        ->paginate(10);
                });
                break;
            case 'bookmarks':
                $data['bookmarks'] = Cache::remember($tabCacheKey, 3600, function () use ($end_user) {
                    return $end_user->bookmarks()
                        ->with(['property'])
                        ->latest()
                        ->paginate(10);
                });
                break;
        }

        return Inertia::render('Dashboard/End-User/Show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $endUser)
    {
        return Inertia::render('Dashboard/End-User/Edit', ['endUser' => $endUser]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $end_user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact' => 'required|string|min:10|max:20',
            'avatar'       => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($end_user->id)
            ],
            'username' => [
                'required',
                'string',
                'alpha_num',
                'max:255',
                Rule::unique('users', 'username')->ignore($end_user->id)
            ],
        ]);

        if ($request->hasFile('avatar')) {
            $end_user->updateProfilePhoto($request['avatar']);
        }

        $end_user->update([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'contact' => $request->contact,
            'profile_photo_path' => $end_user->profile_photo_path,
        ]);

        // Clear user cache after update
        $this->clearUserCache($end_user);

        return redirect()->route('end-users.index')->with("success", "End User Updated Successfully.");
    }

    /**
     * Clear user data cache when user is updated
     */
    private function clearUserCache(User $user)
    {
        $userCacheKey = "user_{$user->id}_data";
        Cache::forget("{$userCacheKey}_counts");
        Cache::forget("{$userCacheKey}_applications");
        Cache::forget("{$userCacheKey}_hiring-requests");
        Cache::forget("{$userCacheKey}_transactions");
        Cache::forget("{$userCacheKey}_bookmarks");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $end_user)
    {
        // Clear user cache before deletion
        $this->clearUserCache($end_user);

        $end_user->delete();
        return redirect()->route("end-users.index")->with("success", "End User deleted successfully.");
    }
}
