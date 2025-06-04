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
use App\Http\Controllers\MessageController;
use App\Http\Controllers\BookmarkController;
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

    // Bookmark routes
    Route::get('/bookmarks', [BookmarkController::class, 'index'])->name('bookmarks.index');
    Route::post('/bookmarks/{property}', [BookmarkController::class, 'toggle'])->name('bookmarks.toggle');
    Route::delete('/bookmarks/{property}', [BookmarkController::class, 'destroy'])->name('bookmarks.destroy');
    Route::get('/bookmarks/{property}', [BookmarkController::class, 'show'])->name('bookmarks.show');

    // Admin/Agent bookmark routes
    Route::middleware(['role:admin|agent'])->group(function () {
        Route::get('/properties/bookmarks', [BookmarkController::class, 'adminIndex'])->name('bookmarks.admin.index');
        Route::get('/properties/{property}/bookmarks', [BookmarkController::class, 'showBookmarkedUsers'])->name('bookmarks.users');
    });

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

    // Message Routes
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/latest-chats', [MessageController::class, 'getLatestChats'])->name('messages.latest-chats');
    Route::get('/messages/search-users', [MessageController::class, 'searchUsers'])->name('messages.search-users');
    Route::get('/messages/search', [MessageController::class, 'search'])->name('messages.search');
    Route::get('/messages/notifications', [MessageController::class, 'notifications'])->name('messages.notifications');
    Route::post('/messages/mark-all-read', [MessageController::class, 'markAllAsRead'])->name('messages.mark-all-read');
    Route::post('/messages/{user}/mark-read', [MessageController::class, 'markChatAsRead'])->name('messages.mark-chat-read');
    Route::get('/messages/{user}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{user}', [MessageController::class, 'store'])->name('messages.store');
    Route::delete('/messages/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');
    Route::post('/messages/{message}/read', [MessageController::class, 'markAsRead'])->name('messages.read');
    Route::post('/messages/{message}/attachments', [MessageController::class, 'storeAttachment'])->name('messages.attachments.store');
    Route::delete('/messages/{message}/attachments/{attachment}', [MessageController::class, 'destroyAttachment'])->name('messages.attachments.destroy');
});

// Property routes for both agents and admins
Route::middleware(['auth', 'verified'])->group(function () {
    Route::middleware(['role:admin|agent'])->group(function () {
        Route::resource('properties', PropertyController::class)->except(['update']);
        Route::post('/properties/{property}', [PropertyController::class, 'update'])->name('properties.update');
        Route::delete('/uploads/{upload}', [PropertyController::class, 'destroyUpload'])->name('uploads.destroy');
    });
});
