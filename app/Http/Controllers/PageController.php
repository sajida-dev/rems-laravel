<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Amenity;
use App\Models\Category;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PageController extends Controller
{
    public function home()
    {
        $properties = Property::whereHas('bookmark', function ($query) {
            $query->where('user_id', Auth::id());
        })
            ->with(['bookmark' => function ($query) {
                $query->where('user_id', Auth::id());
            }])
            ->latest()
            ->take(6)
            ->get();
        $agents = Agent::with('user')->latest()->take(6)->get();
        return Inertia::render(
            'Public/Home',
            [
                'properties' => $properties,
                'agents' => $agents,
            ]
        );
    }

    // , [
    //         'canLogin' => Route::has('login'),
    //         'canRegister' => Route::has('register'),
    //         'laravelVersion' => Application::VERSION,
    //         'phpVersion' => PHP_VERSION,
    //     ]

    public function about()
    {
        return Inertia::render('Public/About');
    }

    public function agent()
    {
        $agents = Agent::with(['user', 'properties.category'])->latest()->take(12)->get();
        return Inertia::render(
            'Public/Agents',
            [
                'agents' => $agents,
            ]
        );
    }

    public function agentDetails($id, $slug)
    {
        $agent = Agent::with(['user', 'categories', 'properties'])->findOrFail($id);
        $expectedSlug = strtolower(str_replace(' ', '-', $agent->user->name));
        if ($slug !== $expectedSlug) {
            return redirect()->route('agents');
        }


        return Inertia::render('Public/AgentDetails', [
            'agent' => $agent,
        ]);
        // return Inertia::render('Public/AgentDetails');
    }

    public function services()
    {
        return Inertia::render('Public/Services');
    }

    // public function properties()
    // {
    //     $allAmenities = Amenity::all();
    //     $categories = Category::all();
    //     $properties = Property::with([
    //         'amenities',
    //         'category',
    //         'bookmark' => function ($query) {
    //             $query->where('user_id', Auth::id());
    //         }
    //     ])
    //         ->latest()
    //         ->take(12)
    //         ->get();
    //     return Inertia::render(
    //         'Public/Properties',
    //         [
    //             'properties' => $properties,
    //             'categories' => $categories,
    //             'allAmenities' => $allAmenities,
    //         ]
    //     );
    // }



    public function properties(Request $request)
    {
        $allAmenities = Amenity::all();
        $categories = Category::all();

        $query = Property::with([
            'amenities',
            'category',
            'bookmark' => function ($query) {
                $query->where('user_id', Auth::id());
            }
        ]);

        if ($search = $request->input('search')) {
            $query->where('title', 'like', "%$search%");
        }

        if ($categoryId = $request->input('category_id')) {
            $query->where('category_id', $categoryId);
        }

        if ($location = $request->input('location')) {
            $query->where('location', 'like', "%$location%");
        }

        if ($request->filled('rent_min_price')) {
            $query->where('rent_price', '>=', $request->rent_min_price);
        }
        if ($request->filled('rent_max_price')) {
            $query->where('rent_price', '<=', $request->rent_max_price);
        }
        if ($request->filled('purchase_min_price')) {
            $query->where('purchase_price', '>=', $request->purchase_min_price);
        }
        if ($request->filled('purchase_max_price')) {
            $query->where('purchase_price', '<=', $request->purchase_max_price);
        }

        if ($bedrooms = $request->input('bedrooms')) {
            $query->where('bedrooms', $bedrooms);
        }
        if ($request->filled('bathrooms')) {
            $query->where('bathrooms', $request->bathrooms);
        }

        if ($request->filled('stories')) {
            $query->where('stories', $request->stories);
        }

        if ($request->filled('lot_area')) {
            $query->where('lot_area', '>=', $request->lot_area);
        }

        if ($request->filled('floor_area')) {
            $query->where('floor_area', '>=', $request->floor_area);
        }

        if ($request->filled('year_built')) {
            $query->where('year_built', $request->year_built);
        }

        if ($request->filled('is_water')) {
            $query->where('is_water', (bool) $request->is_water);
        }

        if ($request->filled('is_new_roofing')) {
            $query->where('is_new_roofing', (bool) $request->is_new_roofing);
        }

        if ($request->filled('garage')) {
            $query->where('garage', '>=', $request->garage);
        }

        if ($request->filled('is_luggage')) {
            $query->where('is_luggage', (bool) $request->is_luggage);
        }

        if ($amenities = $request->input('amenities')) {
            $query->whereHas('amenities', function ($q) use ($amenities) {
                $q->whereIn('amenities.id', $amenities);
            });
        }

        $properties = $query->latest()->take(12)->get();

        return Inertia::render('Public/Properties', [
            'properties' => $properties,
            'categories' => $categories,
            'allAmenities' => $allAmenities,
            'initialFilters' => $request->all(),
        ]);
    }


    public function propertyDetails($id, $slug)
    {
        // $property = Property::findOrFail($id);
        $property = Property::with(['category', 'amenities', 'uploads'])->findOrFail($id);


        // Optional: Validate slug
        $expectedSlug = strtolower(str_replace(' ', '-', $property->title));
        if ($slug !== $expectedSlug) {
            return redirect()->route('properties');
        }
        return Inertia::render(
            'Public/PropertyDetails',
            [
                'property' => $property,
            ]
        );
    }

    public function filterProperties()
    {
        return Inertia::render('Public/PropertyFilter');
    }

    public function contact()
    {
        return Inertia::render('Public/Contact');
    }
}
