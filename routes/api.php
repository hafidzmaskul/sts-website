<?php

use App\Http\Controllers\Api\ContactSubmissionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/contact-submissions', [ContactSubmissionController::class, 'store']);
Route::post('/quotes', [\App\Http\Controllers\Api\QuoteController::class, 'store']);
Route::post('/sign-up', [\App\Http\Controllers\CustomerRegistrationController::class, 'store']);
Route::post('/guest-register', [\App\Http\Controllers\Api\GuestController::class, 'store']);
Route::post('/send-email', [\App\Http\Controllers\Api\EmailController::class, 'send']);

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
    Route::get('/news', [\App\Http\Controllers\Api\NewsController::class, 'index']);
    Route::get('/news/{slug}', [\App\Http\Controllers\Api\NewsController::class, 'show']);
});
Route::middleware(\App\Http\Middleware\SanctumOrBasic::class)->group(function () {
    Route::post('/transactions', [\App\Http\Controllers\Api\TransactionController::class, 'store']);
    Route::get('/transactions/summary', [\App\Http\Controllers\Api\TransactionController::class, 'summary']);
    Route::get('/transactions', [\App\Http\Controllers\Api\TransactionController::class, 'index']);
    Route::get('/transactions/{id}', [\App\Http\Controllers\Api\TransactionController::class, 'show']);
});
Route::get('/product-categories', [\App\Http\Controllers\Api\ProductCategoryController::class, 'index']);
Route::get('/settings', [\App\Http\Controllers\Api\SettingsController::class, 'index']);
Route::post('/product-requests', [\App\Http\Controllers\Api\ProductRequestController::class, 'store']);

Route::middleware(\App\Http\Middleware\SanctumOrBasic::class)->group(function () {
    Route::get('/liked-products', [\App\Http\Controllers\Api\ProductLikeController::class, 'index']);
    Route::post('/products/like', [\App\Http\Controllers\Api\ProductLikeController::class, 'like']);
    Route::post('/products/like', [\App\Http\Controllers\Api\ProductLikeController::class, 'like']);
    Route::post('/products/like', [\App\Http\Controllers\Api\ProductLikeController::class, 'like']);
    Route::post('/products/unlike', [\App\Http\Controllers\Api\ProductLikeController::class, 'unlike']);
    Route::get('/coupons', [\App\Http\Controllers\Api\CouponController::class, 'index']);
    Route::get('/coupons/{code}', [\App\Http\Controllers\Api\CouponController::class, 'show']);
    Route::get('/coupons/{code}', [\App\Http\Controllers\Api\CouponController::class, 'show']);
    Route::post('/rma-requests', [\App\Http\Controllers\Api\RmaRequestController::class, 'store']);
    Route::post('/feedback', [\App\Http\Controllers\Api\FeedbackController::class, 'store']);

    // User Management (Staff)
    Route::prefix('users')->controller(\App\Http\Controllers\Api\UserManagementController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('/{id}', 'show');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
    });
});

Route::controller(\App\Http\Controllers\Api\ShippingAddressController::class)
    ->middleware(\App\Http\Middleware\SanctumOrBasic::class)
    ->prefix('shipping-addresses')
    ->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('/{id}', 'show');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
    });

// Authentication Routes
Route::post('/auth/forgot-password', [\App\Http\Controllers\Api\AuthController::class, 'forgotPassword']);
Route::post('/auth/reset-password', [\App\Http\Controllers\Api\AuthController::class, 'resetPassword']);
Route::post('/login', [\App\Http\Controllers\Api\AuthController::class, 'login']);
Route::post('/register', [\App\Http\Controllers\Api\RegisterController::class, 'register']);
Route::get('/check-email', [\App\Http\Controllers\Api\RegisterController::class, 'checkEmail']);

Route::middleware(\App\Http\Middleware\SanctumOrBasic::class)->group(function () {
    Route::get('/me', [\App\Http\Controllers\Api\AuthController::class, 'me']);
    Route::put('/me', [\App\Http\Controllers\Api\AuthController::class, 'updateProfile']);
});

Route::middleware(\App\Http\Middleware\SanctumOrBasic::class)->group(function () {
    Route::post('/logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);
    Route::post('/auth/change-password', [\App\Http\Controllers\Api\AuthController::class, 'changePassword']);

    // Transactions
    Route::get('/my-transactions', [\App\Http\Controllers\Api\TransactionController::class, 'index']);
    Route::get('/my-transactions/{id}', [\App\Http\Controllers\Api\TransactionController::class, 'show']);
});

// Public Routes
Route::post('/newsletter-subscription', [\App\Http\Controllers\Api\NewsletterSubscriptionController::class, 'store']);
Route::post('/careers', [\App\Http\Controllers\Api\CareerSubmissionController::class, 'store']);
Route::post('/contact', [\App\Http\Controllers\Api\ContactSubmissionController::class, 'store']); // Alias for testing
Route::post('/quote-builders', [\App\Http\Controllers\Api\QuoteBuilderController::class, 'store']); // Alias for testing
