<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactSubmission;
use App\Models\Setting; // Import Setting model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // Import Log
use Illuminate\Support\Facades\Mail; // Import Mail
use Illuminate\Support\Facades\Validator;

class ContactSubmissionController extends Controller
{
    /**
     * Store a newly created contact submission.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422); // Unprocessable Entity
        }

        try {
            // 1. Create the submission record
            $submission = ContactSubmission::create($validator->validated());

            // 2. Send Notification Email
            $this->sendNotificationEmail($submission);

            return response()->json([
                'success' => true,
                'message' => 'Your message has been sent successfully!',
                'data' => $submission,
            ], 201); // Created

        } catch (\Exception $e) {
            // Handle potential database errors
            Log::error('Contact Submission Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while sending your message. Please try again.',
            ], 500); // Internal Server Error
        }
    }

    /**
     * Send an email notification to the admin.
     */
    private function sendNotificationEmail(ContactSubmission $submission)
    {
        // Get Admin Email from Database (General Settings)
        $adminEmailSetting = Setting::where('key', 'email_notification_admin')->first();

        // Fallback to .env mail_from_address if setting is missing
        $adminEmail = $adminEmailSetting ? $adminEmailSetting->value : config('mail.from.address');

        if (!$adminEmail) {
            return;
        }

        $fullName = $submission->first_name . ' ' . $submission->last_name;

        // Construct Email Body
        $emailBody = implode("\n", [
            "New Contact Form Submission",
            "===========================",
            "",
            "Name: " . $fullName,
            "Email: " . $submission->email,
            "Phone: " . ($submission->phone ?? 'N/A'),
            "Subject: " . $submission->subject,
            "",
            "Message:",
            "---------------------------",
            $submission->message,
            "---------------------------",
            "",
            "Received at: " . $submission->created_at->format('Y-m-d H:i:s'),
        ]);

        // Send Email
        try {
            Mail::raw($emailBody, function ($m) use ($adminEmail, $submission, $fullName) {
                $m->to($adminEmail)
                    ->replyTo($submission->email, $fullName) // Allow admin to reply directly to user
                    ->subject('New Contact: ' . $submission->subject);
            });
        } catch (\Throwable $e) {
            // Log email failure but don't fail the request
            Log::error('Failed to send contact notification email: ' . $e->getMessage());
        }
    }
}