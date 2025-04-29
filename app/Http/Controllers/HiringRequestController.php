<?php

namespace App\Http\Controllers;

use App\Models\HiringRequest;
use App\Http\Requests\StoreHiringRequestRequest;
use App\Http\Requests\UpdateHiringRequestRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HiringRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = HiringRequest::query();
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
        $hiringRequest = $query->paginate($perPage, ['*'], 'page', $page)
            ->appends($request->all());
        return Inertia::render(
            "Dashboard/Hiring-Request/Index",
            ['hiringRequest' => $hiringRequest]
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreHiringRequestRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(HiringRequest $hiringRequest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HiringRequest $hiringRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHiringRequestRequest $request, HiringRequest $hiringRequest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HiringRequest $hiringRequest)
    {
        //
    }
}
