<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class FeedbackController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        Feedback::create([
            'user_id' => Auth::id(),
            'message' => $request->message,
        ]);

        $this->sendNotificationEmail($request->message);

        return response()->json(['message' => 'Feedback submitted successfully'], 201);
    }

    private function sendNotificationEmail(string $message)
    {
        $adminEmailSetting = Setting::where('key', 'email_notification_admin')->first();
        $adminEmail = $adminEmailSetting ? $adminEmailSetting->value : config('mail.from.address');

        if (! $adminEmail) {
            return;
        }

        $user = Auth::user();
        $userName = $user ? $user->name : 'Anonymous';
        $userEmail = $user ? $user->email : 'N/A';

        $emailBody = implode("\n", [
            'New User Feedback Received',
            '========================',
            '',
            'User    : '.$userName.' ('.$userEmail.')',
            'Message :',
            '---------',
            $message,
            '---------',
            '',
            'Date: '.now()->format('Y-m-d H:i:s'),
        ]);

        try {
            Mail::raw($emailBody, function ($m) use ($adminEmail, $userEmail, $userName) {
                $m->to($adminEmail)
                    ->replyTo($userEmail !== 'N/A' ? $userEmail : $adminEmail, $userName)
                    ->subject('New Feedback from '.$userName);
            });
        } catch (\Throwable $e) {
            Log::error('Failed to send Feedback notification email: '.$e->getMessage());
        }
    }
}
