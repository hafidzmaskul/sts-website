<?php

use App\Http\Controllers\Api\NewsletterSubscriptionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/subscribe', [NewsletterSubscriptionController::class, 'store'])->name('api.subscribe');