<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ContactSubmissionController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/contact-submissions', [ContactSubmissionController::class, 'store']);
Route::post('/quotes', [\App\Http\Controllers\Api\QuoteController::class, 'store']);
Route::post('/sign-up', [\App\Http\Controllers\CustomerRegistrationController::class, 'store']);

Route::controller(\App\Http\Controllers\Api\QuoteBuilderController::class)
    ->middleware(\App\Http\Middleware\SanctumOrBasic::class)
    ->prefix('quote-builder')
    ->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
        Route::post('/{id}/products', 'addProduct');
        Route::delete('/{id}/products/{productId}', 'removeProduct');
    });

Route::controller(\App\Http\Controllers\Api\CartController::class)
    ->middleware(\App\Http\Middleware\SanctumOrBasic::class)
    ->prefix('cart')
    ->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::post('/decrease', 'decrease');
        Route::delete('/{productId}', 'destroy');
    });

Route::middleware(\App\Http\Middleware\OptionalAuth::class)->group(function () {
    Route::get('/products', [\App\Http\Controllers\Api\ProductController::class, 'index']);
    Route::get('/products/{slug}', [\App\Http\Controllers\Api\ProductController::class, 'show']);
});
Route::middleware(\App\Http\Middleware\SanctumOrBasic::class)->group(function () {
    Route::post('/transactions', [\App\Http\Controllers\Api\TransactionController::class, 'store']);
});
Route::get('/product-categories', [\App\Http\Controllers\Api\ProductCategoryController::class, 'index']);
Route::get('/settings', [\App\Http\Controllers\Api\SettingsController::class, 'index']);


// Authentication Routes
Route::post('/login', [\App\Http\Controllers\Api\AuthController::class, 'login']);
Route::post('/register', [\App\Http\Controllers\Api\RegisterController::class, 'register']);


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [\App\Http\Controllers\Api\AuthController::class, 'me']);
    Route::post('/logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);

    // Transactions
    Route::get('/my-transactions', [\App\Http\Controllers\Api\TransactionController::class, 'index']);
    Route::get('/my-transactions/{id}', [\App\Http\Controllers\Api\TransactionController::class, 'show']);
});


