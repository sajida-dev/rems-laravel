<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Property;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PageController extends Controller
{
    public function home()
    {
        $properties = Property::latest()->take(6)->get();

        return Inertia::render('Public/Home', ['properties' => $properties]);
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
        return Inertia::render('Public/Agents');
    }

    public function agentDetails($id, $slug)
    {
        // $agent = Agent::findOrFail($id);

        // Optional: Validate slug
        // $expectedSlug = strtolower(str_replace(' ', '-', $agent->name));
        // if ($slug !== $expectedSlug) {
        //     return redirect()->route('agent.show', ['id' => $agent->id, 'slug' => $expectedSlug]);
        // }

        // return Inertia::render('Public/AgentDetails', [
        //     'agent' => $agent,
        // ]);
        return Inertia::render('Public/AgentDetails');
    }

    public function services()
    {
        return Inertia::render('Public/Services');
    }

    public function properties()
    {
        return Inertia::render('Public/Properties');
    }

    public function propertyDetails($id, $slug)
    {
        return Inertia::render('Public/PropertyDetails');
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
