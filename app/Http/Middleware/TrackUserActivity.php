<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class TrackUserActivity
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();
            $cacheKey = 'user-is-online-' . $user->id;

            // Set user as online
            Cache::put($cacheKey, true, now()->addMinutes(5));
        }

        return $next($request);
    }
}
