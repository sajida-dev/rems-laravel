<?php

namespace App\Http\Controllers;

use App\Mail\NewApplicationNotification;
use App\Models\Application;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $agent = $user->agent;
        $perPage = $request->perPage ?? 10;
        $page = $request->page ?? 1;

        $applications = Application::whereHas('property', function ($query) use ($agent) {
            $query->where('agent_id', $agent->id);
        })
            ->with(['user', 'property'])
            ->latest()
            ->paginate($perPage, ['*'], 'page', $page)
            ->appends($request->except('page'));
        // ->get();
        return Inertia::render("Dashboard/Application/Index", [
            'applications' => $applications,
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
    public function store(Request $request)
    {
        $request->validate([
            'property_id' => 'required|exists:properties,id',
            'type' => 'required|in:rent,buy'
        ]);

        $user = auth()->user();
        $property = Property::with('agent.user')->findOrFail($request->property_id);

        Application::create([
            'user_id' => auth()->id(),
            'property_id' => $request->property_id,
            'type' => $request->type,
            'status' => 'pending'
        ]);
        if ($property->agent && $property->agent->user && $property->agent->user->email) {
            Mail::to($property->agent->user->email)->send(new NewApplicationNotification($property, $request->type, $user));
        }

        return redirect()->back()->with('success', 'Application sent. Await confirmation.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Application $application)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Application $application)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Application $application)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Application $application)
    {
        //
    }
}
