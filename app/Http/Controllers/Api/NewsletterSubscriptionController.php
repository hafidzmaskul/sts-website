<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscription;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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

            $this->sendNotificationEmail($request->email);

            return response()->json([
                'success' => true,
                'message' => 'Thank you for subscribing!',
                'data' => $subscription,
            ], 201); // Created

        } catch (\Exception $e) {
            Log::error('Newsletter subscription failed: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while subscribing. Please try again.',
            ], 500); // Internal Server Error
        }
    }

    private function sendNotificationEmail(string $email)
    {
        $adminEmailSetting = Setting::where('key', 'email_notification_admin')->first();
        $adminEmail = $adminEmailSetting ? $adminEmailSetting->value : config('mail.from.address');

        if (! $adminEmail) {
            return;
        }

        $emailBody = implode("\n", [
            'New Newsletter Subscription',
            '===========================',
            '',
            'Subscriber Email: '.$email,
            '',
            'Date: '.now()->format('Y-m-d H:i:s'),
        ]);

        try {
            Mail::raw($emailBody, function ($m) use ($adminEmail, $email) {
                $m->to($adminEmail)
                    ->replyTo($email, 'Subscriber')
                    ->subject('New Newsletter Subscriber: '.$email);
            });
        } catch (\Throwable $e) {
            Log::error('Failed to send Newsletter notification email: '.$e->getMessage());
        }
    }
}
