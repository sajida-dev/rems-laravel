<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Inertia::render('Dashboard/End-User/Show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return redirect()->route("end-users.index")->with("success", "End User updated Successfully.");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
