<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class TrackUserActivity
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();
            $cacheKey = 'user-is-online-' . $user->id;

            // Set user as online with a shorter expiration
            Cache::put($cacheKey, true, now()->addSeconds(10));

            $sessionId = $request->session()->getId();

            // Update session with user ID and current timestamp
            DB::table('sessions')
                ->where('id', $sessionId)
                ->update([
                    'user_id' => $user->id,
                    'last_activity' => time()
                ]);

            // Broadcast user's online status
            broadcast(new \App\Events\UserStatusChanged($user, true))->toOthers();
        }

        return $next($request);
    }
}
