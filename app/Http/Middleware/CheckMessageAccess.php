<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMessageAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // If user is admin, redirect to dashboard
        if ($user->role === 'admin') {
            return redirect()->route('dashboard')->with('error', 'Admins cannot access messages.');
        }

        // If user is not logged in, redirect to home
        if (!$user) {
            return redirect()->route('home');
        }

        return $next($request);
    }
}
