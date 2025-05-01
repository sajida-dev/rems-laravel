<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Http\Requests\StoreAgentRequest;
use App\Http\Requests\UpdateAgentRequest;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AgentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index(Request $request)
    // {
    //     $query = User::query();
    //     $query->where('role', 'agent');
    //     if ($request->filled('global')) {
    //         $search = $request->global;
    //         $query->where(function ($q) use ($search) {
    //             $q->where('id', $search)
    //                 ->orwhere('name', 'LIKE', "%{$search}%")
    //                 ->orwhere('email', 'LIKE', "%{$search}%")
    //                 ->orwhere('contact', 'LIKE', "%{$search}%")
    //                 ->orwhere('role', 'LIKE', "%{$search}%")
    //             ;
    //         });
    //     }


    //     if ($request->filled('sortBy')) {
    //         $direction = $request->sortBy === 'true' ? "desc" : "asc";
    //         $query->orderBy($query->sortBy, $direction);
    //     } else {
    //         $query->latest('created_at');
    //     }

    //     $perPage = $request->perPage ?? 10;
    //     $page = $request->page ?? 1;
    //     $agents = $query->paginate($perPage, ['*'], 'page', $page)
    //         ->appends($request->all());
    //     return Inertia::render(
    //         "Dashboard/Agent/Index",
    //         ['agents' => $agents]
    //     );
    // }
    public function index(Request $request)
    {
        $query = User::with(['agent'])
            ->where('role', 'agent');


        // Global search on users and agents table
        if ($request->filled('global')) {
            $search = $request->global;

            $query->where(function ($q) use ($search) {
                $q->where('id', $search)
                    ->orWhere('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('contact', 'LIKE', "%{$search}%");

                $q->orWhereHas('agent', function ($agentQuery) use ($search) {
                    $agentQuery->where('licence_no', 'LIKE', "%{$search}%")
                        ->orWhere('agency', 'LIKE', "%{$search}%")
                        ->orWhere('experience', 'LIKE', "%{$search}%")
                        ->orWhere('bio', 'LIKE', "%{$search}%");
                });
            });
        }

        // Sorting
        if ($request->filled('sortBy')) {
            $direction = $request->sortBy === 'true' ? 'desc' : 'asc';
            $sortField = $request->get('sortField', 'created_at');
            $query->orderBy($sortField, $direction);
        } else {
            $query->latest('created_at');
        }

        // Pagination
        $perPage = $request->perPage ?? 10;
        $page = $request->page ?? 1;

        $agents = $query->paginate($perPage, ['*'], 'page', $page)
            ->appends($request->all());
        return Inertia::render("Dashboard/Agent/Index", [
            'agents' => $agents,
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all(['id', 'name'])->map(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
            ];
        });
        return Inertia::render("Dashboard/Agent/Create", ['categories' => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAgentRequest $request)
    {
        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => 'agent',
            'contact' => $request->contact,
            'password' => bcrypt('password'),
            'profile_photo_path' => $avatarPath,
        ]);


        $agent = Agent::create([
            'user_id' => $user->id,
            'licence_no' => $request->licence_no,
            'agency' => $request->agency,
            'experience' => $request->experience,
            'bio' => $request->bio,
            'status' => $request->status ?? 0,

        ]);

        if ($request->filled('categories')) {
            $agent->categories()->sync($request->categories);
        }

        return redirect()->route("agents.index")->with("success", "Agent inserted successfully.");
    }
    /**
     * Display the specified resource.
     */
    public function show(User $agent)
    {
        $categories = Category::all(['id', 'name'])->map(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
            ];
        });
        $agent->load('agent.categories');
        return Inertia::render(
            "Dashboard/Agent/Create",
            [
                'agent' => $agent,
                'categories' => $categories
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $agent)
    {
        $categories = Category::all(['id', 'name'])->map(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
            ];
        });
        $agent->load('agent.categories');
        return Inertia::render(
            "Dashboard/Agent/Edit",
            [
                'agent' => $agent,
                'categories' => $categories
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAgentRequest $request, Agent $agent)
    {
        $agent->update([]);
        return redirect()->route("agents.index")->with("success", "Agents inserted Successfully.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Agent $agent)
    {
        $agent->delete();
        return redirect()->route("agents.index")->with("success", "Agents deleted Successfully.");
    }
}
