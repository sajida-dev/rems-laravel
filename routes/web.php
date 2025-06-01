<?php

use App\Http\Controllers\AgentController;
use App\Http\Controllers\AmenityController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EndUserController;
use App\Http\Controllers\HiringRequestController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\NotificationController;
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
Route::get('/all-agents', [PageController::class, 'agent'])->name('agents');
Route::get('/agent/{id}-{name}', [PageController::class, 'agentDetails'])->name('agent-details');
Route::get('/services', [PageController::class, 'services'])->name('services');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');

// Properties Listing
Route::get('/all-properties', [PageController::class, 'properties'])->name('properties');
Route::get('/property/{id}-{title}', [PageController::class, 'propertyDetails'])->name('property-details');


Route::middleware(['auth', 'verified', 'admin'])->group(function () {

    Route::get('/agents/pending-count', function () {
        return response()->json([
            'count' => Agent::where('status', 0)->count()
        ]);
    });


    Route::resource('categories', CategoryController::class)->except(['update']);
    Route::post('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');

    Route::resource('agents', AgentController::class)->except(['update']);
    Route::post('/agents/{agent}', [AgentController::class, 'update'])->name('agents.update');
    Route::post('/agents/{agent}/approve', [AgentController::class, 'approve'])->name('agents.approve');


    Route::resource('amenities', AmenityController::class)->except(['update']);
    Route::post('/amenities/{amenity}', [AmenityController::class, 'update'])->name('amenities.update');

    Route::resource('end-users', EndUserController::class)->except(['update']);
    Route::post('/end-users/{end_user}', [EndUserController::class, 'update'])->name('end-users.update');
});


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/payments', [PaymentController::class, 'index'])->name('payment.index');
    Route::post('/payment/create', [PaymentController::class, 'create'])->name('payment.create');
    Route::post('/payment', [PaymentController::class, 'store'])->name('payment.store');
    Route::post('/payment/createIntent', [PaymentController::class, 'create'])->name('payment.createIntent');

    Route::post('/application', [ApplicationController::class, 'store'])->name('application.store');
    Route::get('/all-applications', [ApplicationController::class, 'index'])->name('application.index');
    Route::post('/application/{application}/approve', [ApplicationController::class, 'update'])->name('application.update');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/bookmarks', [PropertyController::class, 'updateBookMark'])->name('property-bookmark-update');
    Route::delete('/bookmarks/{id}', [PropertyController::class, 'deleteBookMark'])->name('property-bookmark-delete');

    Route::get('/property/{property}/buy', [TransactionController::class, 'createBuy'])->name('buy.create');
    Route::post('/property/{property}/buy', [TransactionController::class, 'storeBuy'])->name('buy.store');
    Route::get('/property/{property}/rent', [TransactionController::class, 'createRent'])->name('rent.create');
    Route::post('/property/{property}/rent', [TransactionController::class, 'storeRent'])->name('rent.store');

    Route::get('/hiring-requests', [HiringRequestController::class, 'index'])->name('hiring-requests.index');
    Route::get('/hiring-requests/{id}-{name}', [HiringRequestController::class, 'create'])->name('hiring-requests.create');
    Route::post('/hiring-requests', [HiringRequestController::class, 'store'])->name('hiring-requests.store');
    Route::get('/hiring-requests/{hiringRequest}', [HiringRequestController::class, 'show'])->name('hiring-requests.show');
    Route::post('/accept-hiring-requests/{hiringRequest}', [HiringRequestController::class, 'accept'])->name('hiring-requests.accept');
    Route::post('/reject-hiring-requests/{hiringRequest}', [HiringRequestController::class, 'reject'])->name('hiring-requests.reject');

    // Notification routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('notifications.unreadCount');
});

// Property routes for both agents and admins
Route::middleware(['auth', 'verified'])->group(function () {
    Route::middleware(['role:admin|agent'])->group(function () {
        Route::resource('properties', PropertyController::class)->except(['update']);
        Route::post('/properties/{property}', [PropertyController::class, 'update'])->name('properties.update');
        Route::delete('/uploads/{upload}', [PropertyController::class, 'destroyUpload'])->name('uploads.destroy');
    });
});
