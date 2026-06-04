<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductRequestController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'message' => 'nullable|string',
            'product_id' => 'required|exists:products,id',
        ]);

        $productRequest = ProductRequest::create($validated);

        // Notify Admins
        $adminEmailSetting = \App\Models\Setting::where('key', 'email_notification_admin')->value('value');

        if ($adminEmailSetting) {
            $emails = array_map('trim', explode(',', $adminEmailSetting));
            foreach ($emails as $email) {
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    \Illuminate\Support\Facades\Mail::to($email)->send(new \App\Mail\NewProductRequestNotification($productRequest));
                }
            }
        }

        return response()->json([
            'message' => 'Product request submitted successfully.',
            'data' => $productRequest,
        ], 201);
    }
}
