<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Http\Requests\StorePropertyRequest;
use App\Http\Requests\UpdatePropertyRequest;
use App\Models\Amenity;
use App\Models\Bookmark;
use App\Models\Category;
use App\Models\Upload;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        // Base query with relationships
        $query = Property::with(['category', 'agent.user']);

        // Agent can only see their own properties
        if ($user->role === 'agent') {
            $query->where('agent_id', $user->agent->id);
        }

        // Global search
        if ($request->filled('global')) {
            $search = $request->global;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%")
                    ->orWhereHas('agent', fn($q) => $q->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('category', fn($q) => $q->where('name', 'like', "%{$search}%"));
            });
        }

        // Sorting
        if ($request->filled('sortField')) {
            $direction = $request->sortAsc === 'true' ? 'asc' : 'desc';
            $query->orderBy($request->sortField, $direction);
        } else {
            $query->latest('created_at');
        }

        // Pagination
        $perPage = $request->perPage ?? 10;
        $properties = $query->paginate($perPage)->appends($request->all());

        return Inertia::render('Dashboard/Property/Index', [
            'properties' => $properties,
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $amenities = Amenity::all(['id', 'name']);
        $categories = Category::all(['id', 'name']);
        return Inertia::render("Dashboard/Property/Create", [
            'amenities' => $amenities,
            'categories' => $categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePropertyRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();

        try {
            $user = Auth::user();
            $validated['agent_id'] = $user->agent->id;
            if ($request->hasFile('main_image')) {
                $mainImagePath = $request->file('main_image')->store('property_images', 'public');
                $validated['image_url'] = $mainImagePath;
            }

            $property = Property::create($validated);

            $property->amenities()->attach($validated['amenities']);


            if ($request->hasFile('other_images')) {
                foreach ($request->file('other_images') as $image) {
                    $path = $image->store('property_uploads', 'public');

                    Upload::create([
                        'property_id' => $property->id,
                        'image_path' => $path,
                        'alt_text' => pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME),
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('properties.index')
                ->with('success', 'Property created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Property $property)
    {
        $property->load(['category', 'amenities', 'uploads']);
        return Inertia::render('Dashboard/Property/Show', [
            'property' => $property
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Property $property)
    {

        $amenities = Amenity::all(['id', 'name']);
        $categories = Category::all(['id', 'name']);
        $property->load(['uploads', 'amenities']);
        return Inertia::render("Dashboard/Property/Edit", [
            'amenities' => $amenities,
            'categories' => $categories,
            'property' => $property,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePropertyRequest $request, Property $property)
    {
        $validated = $request->validated();

        DB::beginTransaction();

        try {
            if ($request->hasFile('main_image')) {
                $mainImagePath = $request->file('main_image')->store('property_images', 'public');
                $validated['image_url'] = $mainImagePath;
            }

            $property->update($validated);

            $property->amenities()->sync($validated['amenities']);


            if ($request->hasFile('other_images')) {
                foreach ($request->file('other_images') as $image) {
                    $path = $image->store('property_uploads', 'public');

                    Upload::create([
                        'property_id' => $property->id,
                        'image_path' => $path,
                        'alt_text' => pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME),
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('properties.index')
                ->with('success', 'Property updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()]);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Property $property)
    {
        DB::beginTransaction();

        try {
            $property->amenities()->detach();

            foreach ($property->uploads as $upload) {
                if (Storage::exists($upload->path)) {
                    Storage::delete($upload->path);
                }
                $upload->delete();
            }

            $property->delete();

            DB::commit();

            return redirect()->back()->with('success', 'Property deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Failed to delete property: ' . $e->getMessage());
        }
    }


    public function destroyUpload(Upload $upload)
    {
        Storage::disk('public')->delete($upload->image_path);

        $upload->delete();

        return response()->noContent();
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
