<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NewsletterSubscriptionController extends Controller
{
    /**
     * Store a newly created newsletter subscription.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:newsletter_subscriptions,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422); // Unprocessable Entity
        }

        try {
            $subscription = NewsletterSubscription::create([
                'email' => $request->email,
                'is_subscribe' => true,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Thank you for subscribing!',
                'data' => $subscription,
            ], 201); // Created

        } catch (\Exception $e) {
            // Handle potential database errors
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while subscribing. Please try again.',
            ], 500); // Internal Server Error
        }
    }
}