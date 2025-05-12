<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Laravel\Jetstream\Jetstream;
use Carbon\Carbon;
use stdClass;


class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        $user = Auth::user();
        $agentData = $user->agent;
        $allCategories = Category::all();
        $savedCategories = $agentData
            ? $agentData->categories->pluck('id')->toArray()
            : [];


        // Build the sessions list exactly like Jetstream does
        $sessions = collect(
            DB::table('sessions')
                ->where('user_id', $user->id)
                ->get()
        )->map(fn(stdClass $session) => [
            'agent'            => $session->user_agent,
            'ip_address'       => $session->ip_address,
            'is_current_device' => $session->id === session()->getId(),
            'last_active'      => Carbon::createFromTimestamp($session->last_activity)
                ->diffForHumans(),
        ])->toArray();

        return Inertia::render('Profile/Show', [
            'user'      => $user,
            'agent'     => $agentData,
            'categories' => $allCategories,
            'savedCategories' => $savedCategories,

            // **jetstream props your Vue page expects:**
            'sessions'  => $sessions,
            // 'confirmsTwoFactorAuthentication' => Jetstream::option('confirmPassword'),
            'confirmsTwoFactorAuthentication' => config('fortify.confirmPassword'),

        ]);
    }
}
