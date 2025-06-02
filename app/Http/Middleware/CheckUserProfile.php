<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserProfile
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();

            // Check if user has updated their profile
            if (empty($user->username)) {
                // Allow access to profile routes to prevent redirect loops
                if ($request->is('user/profile*')) {
                    return $next($request);
                }

                return redirect()->route('profile.show')
                    ->with('warning', 'Please update your profile information before continuing.');
            }
        }

        return $next($request);
    }
}
