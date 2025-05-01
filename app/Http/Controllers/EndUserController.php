<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;


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
            $query->orderBy($query->sortBy, $direction);
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
            $avatarPath = '/storage' . '/' . $request->file('avatar')->store('avatars', 'public');
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
    public function show(User $endUser)
    {
        return Inertia::render('Dashboard/End-User/Show', ['endUser' => $endUser]);
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
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
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
            if ($end_user->profile_photo_path && Storage::disk('public')->exists($end_user->profile_photo_path)) {
                Storage::disk('public')->delete($end_user->profile_photo_path);
            }

            $avatarPath = '/storage' . '/' . $request->file('avatar')->store('avatars', 'public');
            $end_user->profile_photo_path = $avatarPath;
        }

        $end_user->update([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'contact' => $request->contact,
            'profile_photo_path' => $end_user->profile_photo_path,
        ]);
        return redirect()->route('end-users.index')->with("success", "End User Updated Successfully.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
