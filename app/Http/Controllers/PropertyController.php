<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Http\Requests\StorePropertyRequest;
use App\Http\Requests\UpdatePropertyRequest;
use App\Models\Bookmark;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $properties = Property::all();

        return Inertia::render('Properties/Index', [
            'properties' => $properties
        ]);
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
    public function store(StorePropertyRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Property $property)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Property $property)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePropertyRequest $request, Property $property)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Property $property)
    {
        //
    }


    public function updateBookMark(Request $request)
    {
        $request->validate([
            'property_id' => 'required|exists:properties,id',
        ]);

        $bookmark = Bookmark::firstOrCreate([
            'user_id' => Auth::id(),
            'property_id' => $request->property_id,
        ]);

        return back(); // Or return response()->json(['success' => true]);
    }

    public function deleteBookMark(Property $property)
    {
        $deleted = Bookmark::where('user_id', Auth::id())
            ->where('property_id', $property->id)
            ->delete();

        return back(); // Or return response()->json(['success' => $deleted > 0]);
    }
}
