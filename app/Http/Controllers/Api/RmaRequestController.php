<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RmaRequest;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class RmaRequestController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'orderNumber' => 'required|string',
            'productName' => 'required|string',
            'returnReason' => 'required|string',
            'returnType' => 'required|string',
            'proofOfPurchase' => 'required|file|mimes:pdf,jpg,jpeg,png,webp|max:10240',
            'comments' => 'nullable|string',
        ]);

        $path = null;
        if ($request->hasFile('proofOfPurchase')) {
            $path = $request->file('proofOfPurchase')->store('rma-proofs', 'public');
        }

        $rmaRequest = RmaRequest::create([
            'user_id' => $request->user()?->id,
            'order_number' => $request->orderNumber,
            'product_name' => $request->productName,
            'return_reason' => $request->returnReason,
            'return_type' => $request->returnType,
            'comments' => $request->comments,
            'proof_of_purchase_path' => $path,
            'status' => 'pending',
        ]);

        $this->sendNotificationEmail($rmaRequest);

        return response()->json([
            'success' => true,
            'message' => 'RMA request submitted successfully.',
            'data' => $rmaRequest,
        ], 201);
    }

    private function sendNotificationEmail(RmaRequest $submission)
    {
        $adminEmailSetting = Setting::where('key', 'email_notification_admin')->first();
        $adminEmail = $adminEmailSetting ? $adminEmailSetting->value : config('mail.from.address');

        if (!$adminEmail) {
            return;
        }

        $emailBody = implode("\n", [
            "New RMA Request Received",
            "========================",
            "",
            "Order Number : " . $submission->order_number,
            "Product Name : " . $submission->product_name,
            "Return Type  : " . $submission->return_type,
            "Return Reason: " . $submission->return_reason,
            "",
            "Comments:",
            "---------",
            $submission->comments ?? "No comments provided.",
            "---------",
            "",
            "Proof of purchase is attached if provided.",
        ]);

        try {
            Mail::raw($emailBody, function ($m) use ($adminEmail, $submission) {
                $userEmail = $submission->user ? $submission->user->email : 'noreply@example.com';
                $userName = $submission->user ? $submission->user->name : 'Customer';

                $m->to($adminEmail)
                    ->replyTo($userEmail, $userName)
                    ->subject('RMA Request: ' . $submission->order_number);

                if ($submission->proof_of_purchase_path) {
                    $filePath = storage_path('app/public/' . $submission->proof_of_purchase_path);
                    if (file_exists($filePath)) {
                        $m->attach($filePath);
                    }
                }
            });
        } catch (\Throwable $e) {
            Log::error('Failed to send RMA notification email: ' . $e->getMessage());
        }
    }
}
