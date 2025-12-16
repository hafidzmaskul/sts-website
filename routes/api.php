<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ContactSubmissionController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/contact-submissions', [ContactSubmissionController::class, 'store']);
Route::post('/quotes', [\App\Http\Controllers\Api\QuoteController::class, 'store']);
