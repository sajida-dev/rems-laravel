<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|  pk_test_51RO0KAJdv4kTQ0M0sNIQEASbPvCBOI9eIAff1KUvp0C4m2FhqEZUHgrkcC6fhZL7zkiRbhjp1UBUzHohJTqzxpq700XpQFryUJ
|  sk_test_51RO0KAJdv4kTQ0M0SNiZONNCXz01TK9l8dmEOOcfd11PeKEXdxZZVGPATGeoW2ICqo3EyXAuFPGVoOJprciMKxcK00JNsAdTJw
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
