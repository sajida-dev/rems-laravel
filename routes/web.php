<?php

use App\Http\Controllers\AmenityController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PropertyController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/agent', [PageController::class, 'agent'])->name('agent');
Route::get('/services', [PageController::class, 'services'])->name('services');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');

// Properties Listing
Route::get('/properties', [PageController::class, 'properties'])->name('properties');

Route::get('/agents/pending-count', function () {
    return response()->json([
        'count' => \App\Models\Agent::where('status', 0)->count()
    ]);
});

Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    // Dashboard route
    Route::get('/dashboard', function () {
        return inertia('Admin/Dashboard');
    })->name('admin.dashboard');

    // Category resource routes
    Route::resource('categories', CategoryController::class);

    // Amenity resource routes
    Route::resource('amenities', AmenityController::class);

    // User management routes for admin
    Route::resource('users', RegisteredUserController::class);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});
