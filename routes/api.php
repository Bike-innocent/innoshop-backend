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
use App\Http\Controllers\Product\ProductCategoryController;
use App\Http\Controllers\Product\BrandController;
use App\Http\Controllers\Product\ColourController;
use App\Http\Controllers\Product\SizeController;
use App\Http\Controllers\Product\SupplierController;
use App\Http\Controllers\Order\CustomerOrderController;
use App\Http\Controllers\Order\CartController;


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

Route::get('/user-addresses', [UserController::class, 'userAddresses']);


Route::get('/products', [ProductController::class, 'index']);

Route::get('/products/{slug}', [ProductController::class, 'show']);


Route::post('/products', [ProductController::class, 'store']);

Route::put('/products/{product}', [ProductController::class, 'update']);
Route::delete('/products/{product}', [ProductController::class, 'destroy']);







// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // User routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [ProfileController::class, 'index']);




});




Route::apiResource('product-categories', ProductCategoryController::class);


// routes/api.php



Route::apiResource('brands', BrandController::class);
Route::post('brands/{id}/restore', [BrandController::class, 'restore']);



Route::apiResource('colours', ColourController::class);
Route::post('colours/{id}/restore', [ColourController::class, 'restore']);




Route::apiResource('sizes', SizeController::class);
Route::post('sizes/{id}/restore', [SizeController::class, 'restore']);



Route::apiResource('suppliers', SupplierController::class);
Route::post('suppliers/restore/{id}', [SupplierController::class, 'restore']);


Route::prefix('orders')->group(function () {
    Route::get('/', [CustomerOrderController::class, 'index']);
    Route::post('/', [CustomerOrderController::class, 'store']);
    Route::get('{id}', [CustomerOrderController::class, 'show']);
    Route::put('{id}', [CustomerOrderController::class, 'update']);
    Route::delete('{id}', [CustomerOrderController::class, 'destroy']);
    Route::patch('{id}/restore', [CustomerOrderController::class, 'restore']);
});




Route::middleware('auth:sanctum')->group(function () {
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart', [CartController::class, 'store']);
    Route::put('/cart/{id}', [CartController::class, 'update']);
    Route::delete('/cart/{id}', [CartController::class, 'destroy']);
});



