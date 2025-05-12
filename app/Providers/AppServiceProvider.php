<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Inertia::share('auth.user', function () {
            /** @var \App\Models\User|null $user */
            $user = Auth::user();

            // return $user instanceof User ? $user->load('agent') : null;
            return $user ? $user->loadMissing('agent') : null;
        });
    }
}
