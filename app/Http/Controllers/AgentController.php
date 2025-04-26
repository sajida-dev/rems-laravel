<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Http\Requests\StoreAgentRequest;
use App\Http\Requests\UpdateAgentRequest;
use Inertia\Inertia;

class AgentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render("Dashboard/Agent/Index");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render("Dashboard/Agent/Create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAgentRequest $request)
    {
        Agent::create([]);
        return redirect()->route("agents.index")->with("success", "Agents inserted Successfully.");
    }

    /**
     * Display the specified resource.
     */
    public function show(Agent $agent)
    {
        return Inertia::render("Dashboard/Agent/Create", ['agent' => $agent]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Agent $agent)
    {
        return Inertia::render("Dashboard/Agent/Edit", ['agent' => $agent]);
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
