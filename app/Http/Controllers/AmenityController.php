<?php

namespace App\Http\Controllers;

use App\Models\Amenity;
use App\Http\Requests\StoreAmenityRequest;
use App\Http\Requests\UpdateAmenityRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AmenityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Amenity::query();

        if ($request->filled('global')) {
            $search = $request->global;
            $query->where(function ($q) use ($search) {
                $q->where('id',    $search)
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Sort
        if ($request->filled('sortBy')) {
            $direction = $request->sortDesc === 'true' ? 'desc' : 'asc';
            $query->orderBy($request->sortBy, $direction);
        } else {
            $query->latest('created_at');
        }

        // Pagination
        $perPage    = $request->perPage  ?? 10;
        $page       = $request->page     ?? 1;
        $amenities = $query
            ->paginate($perPage, ['*'], 'page', $page)
            ->appends($request->all());

        return Inertia::render('Dashboard/Amenity/Index', [
            'amenities' => $amenities,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render("Dashboard/Amenity/Create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAmenityRequest $request)
    {
        Amenity::create([
            'name' => $request->amenityName,
            'description' => $request->amenityDescription
        ]);
        return redirect()->route("amenities.index")->with("success", "Amenities created Successfully.");
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Amenity $amenity)
    {
        return Inertia::render("Dashboard/Amenity/Edit", ['amenity' => $amenity]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAmenityRequest $request, Amenity $amenity)
    {
        $amenity->update([
            "name" => $request->amenityName,
            "description" => $request->amenityDescription,
        ]);
        return redirect()->route("amenities.index")->with("success", "Amenities Updated Successfully.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Amenity $amenity)
    {
        $amenity->delete();
        return redirect()->route("amenities.index")->with("success", "Amenities deleted Successfully.");
    }
}
