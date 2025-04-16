<?php

use App\Http\Controllers\AgentController;
use App\Http\Controllers\AmenityController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PropertyController;
use App\Models\Agent;
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
    // Dashboard route
    Route::get('/dashboard', function () {
        return inertia('Admin/Dashboard');
    })->name('admin.dashboard');

    Route::resource('categories', CategoryController::class);
    // Route::resource('properties', PropertyController::class);
    // Route::resource('agents', AgentController::class);
    Route::resource('amenities', AmenityController::class);

    Route::resource('users', RegisteredUserController::class);
});

Route::middleware('auth')->group(function () {
    Route::post('/bookmarks', [PropertyController::class, 'updateBookMark'])->name('property-bookmark-update');
    Route::delete('/bookmarks/{id}', [PropertyController::class, 'deleteBookMark'])->name('property-bookmark-delete');
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
