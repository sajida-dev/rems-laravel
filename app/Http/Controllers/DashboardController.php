<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     $user = Auth::user();

    //     $sharedStats = [];

    //     if ($user->role === 'agent') {
    //         if ($user->agent != null) {
    //             $sharedStats = [
    //                 'totalProperties'     => Property::where('agent_id', $user->agent->id)->count(),
    //                 'availableProperties' => Property::where('agent_id', $user->agent->id)->where('status', 'available')->count(),
    //                 'soldProperties'      => Property::where('agent_id', $user->agent->id)->where('status', 'sold')->count(),
    //                 'rentedProperties'    => Property::where('agent_id', $user->agent->id)->where('status', 'rented')->count(),
    //             ];
    //         } else {
    //             return redirect()->route('profile'); // jetstream default profile route
    //         }
    //     }

    //     if ($user->role === 'admin') {
    //         $sharedStats = [
    //             'agentsCount'     => User::where('role', 'agent')->count(),
    //             'endUsersCount'   => User::where('role', 'user')->count(),
    //             'propertiesCount' => Property::count(),
    //             'ordersCount'     => Transaction::where('transaction_type', 'buy')->count(),
    //         ];
    //     }

    //     // return response()->json([]);

    //     return Inertia::render('Dashboard/Home', ['stats' => $sharedStats,]);
    // }
    public function index()
    {
        // Eager load the 'agent' relationship to avoid N+1 issues
        $user = Auth::user()->load('agent');

        $sharedStats = [];

        if ($user->role === 'agent') {
            $agent = $user->agent;

            if ($agent) {
                $agentId = $agent->id;

                $sharedStats = [
                    'totalProperties'     => Property::where('agent_id', $agentId)->count(),
                    'availableProperties' => Property::where('agent_id', $agentId)->where('status', 'available')->count(),
                    'soldProperties'      => Property::where('agent_id', $agentId)->where('status', 'sold')->count(),
                    'rentedProperties'    => Property::where('agent_id', $agentId)->where('status', 'rented')->count(),
                ];
            } else {
                // Redirect to profile if no agent relation is found
                return redirect()->route('profile.show');
            }
        }

        if ($user->role === 'admin') {
            $sharedStats = [
                'agentsCount'     => User::where('role', 'agent')->count(),
                'endUsersCount'   => User::where('role', 'user')->count(),
                'propertiesCount' => Property::count(),
                'ordersCount'     => Transaction::where('transaction_type', 'buy')->count(),
            ];
        }

        return Inertia::render('Dashboard/Home', ['stats' => $sharedStats]);
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit() {}

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
