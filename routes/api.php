<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Profile\ProfileController;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Http\Controllers\CsrfCookieController;
use App\Http\Controllers\Contact\ContactController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Product\ProductController;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail']);
Route::post('/reset-password', [ResetPasswordController::class, 'reset']);
Route::get('/sanctum/csrf-cookie', [CsrfCookieController::class, 'show']);


// Project routes

// Blog routes





Route::post('/contact', [ContactController::class, 'sendContactMessage']);






Route::get('/users', [UserController::class, 'index']);
Route::get('/products', [ProductController::class, 'index']);

Route::get('/products/{slug}', [ProductController::class, 'show']);













// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // User routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [ProfileController::class, 'index']);




});
