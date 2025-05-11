<?php

namespace App\Http\Controllers;

use App\Models\Agent;
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
        $agent = Agent::findOrFail($id);

        // Optional: Validate slug
        $expectedSlug = strtolower(str_replace(' ', '-', $agent->name));
        if ($slug !== $expectedSlug) {
            return redirect()->route('agent.show', ['id' => $agent->id, 'slug' => $expectedSlug]);
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

    public function properties()
    {
        // $properties = Property::with(['amenities', 'category', 'bookmark'])->latest()->take(12)->get();
        $properties = Property::with([
            'amenities',
            'category',
            'bookmark' => function ($query) {
                $query->where('user_id', Auth::id());
            }
        ])
            ->latest()
            ->take(12)
            ->get();
        return Inertia::render(
            'Public/Properties',
            [
                'properties' => $properties
            ]
        );
    }

    public function propertyDetails($id, $slug)
    {
        // $property = Property::findOrFail($id);
        $property = Property::with(['category', 'amenities', 'uploads'])->findOrFail($id);


        // Optional: Validate slug
        $expectedSlug = strtolower(str_replace(' ', '-', $property->title));
        if ($slug !== $expectedSlug) {
            return redirect()->route('properties.show', ['id' => $property->id, 'slug' => $expectedSlug]);
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
