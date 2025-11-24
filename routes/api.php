<?php

use App\Http\Controllers\Api\CareerSubmissionController;
use App\Http\Controllers\Api\ContactSubmissionController;
use App\Http\Controllers\Api\NewsletterSubscriptionController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\UploadController;
use App\Http\Controllers\Api\WebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/subscribe', [NewsletterSubscriptionController::class, 'store'])->name('api.subscribe');
Route::post('/contact', [ContactSubmissionController::class, 'store'])->name('api.contact.store');

Route::post('/careers/apply', [CareerSubmissionController::class, 'store'])->name('api.careers.apply');

Route::post('/checkout', [TransactionController::class, 'store'])->name('api.checkout.store');
Route::post('/webhooks/square', [WebhookController::class, 'handleSquare'])->name('api.webhooks.square');
Route::post('/editor/upload', [UploadController::class, 'upload'])->name('api.editor.upload');