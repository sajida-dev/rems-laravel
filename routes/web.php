<?php

use App\Http\Controllers\AgentController;
use App\Http\Controllers\AmenityController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EndUserController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PropertyController;
use App\Models\Agent;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;

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



Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/all-agents', [PageController::class, 'agent'])->name('agent');
Route::get('/agent/{id}-{name}', [PageController::class, 'agentDetails'])->name('agent-details');
Route::get('/services', [PageController::class, 'services'])->name('services');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');

// Properties Listing
Route::get('/all-properties', [PageController::class, 'properties'])->name('properties');
Route::get('/filter-properties', [PageController::class, 'filterProperties'])->name('properties.filter');
Route::get('/property/{id}-{title}', [PageController::class, 'propertyDetails'])->name('property-details');

Route::get('/agents/pending-count', function () {
    return response()->json([
        'count' => Agent::where('status', 0)->count()
    ]);
});

Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::resource('categories', CategoryController::class)->except(['update']);
    Route::resource('properties', PropertyController::class)->except(['update']);
    Route::resource('agents', AgentController::class)->except(['update']);
    Route::resource('amenities', AmenityController::class)->except(['update']);
    Route::resource('end-users', EndUserController::class)->except(['update']);
    Route::post('/properties/{property}', [PropertyController::class, 'update'])->name('properties.update');
    Route::post('/end-users/{end_user}', [EndUserController::class, 'update'])->name('end-users.update');
});

Route::post('/agents/{agent}', [AgentController::class, 'update'])->name('agents.update');

Route::get('/debug-auth', function () {
    return auth()->user();
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/bookmarks', [PropertyController::class, 'updateBookMark'])->name('property-bookmark-update');
    Route::delete('/bookmarks/{id}', [PropertyController::class, 'deleteBookMark'])->name('property-bookmark-delete');
});
