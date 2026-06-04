<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CareerSubmission;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class CareerSubmissionController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'career_id' => 'required|integer|exists:careers,id',
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'mobile_phone' => 'required|string|max:30',
            'resume' => 'required|file|mimes:pdf,doc,docx,txt,rtf|max:5120',
            'message' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $validated = $validator->validated();

            $resumePath = null;
            if ($request->hasFile('resume')) {
                $resumePath = $request->file('resume')->store('career-submissions', 'public');
            }

            $submission = CareerSubmission::create([
                'career_id' => $validated['career_id'],
                'full_name' => $validated['full_name'],
                'email' => $validated['email'],
                'mobile_phone' => $validated['mobile_phone'],
                'resume_path' => $resumePath,
                'message' => $validated['message'] ?? null,
                'status' => 'pending',
            ]);

            $this->sendNotificationEmail($submission);

            return response()->json([
                'success' => true,
                'message' => 'Your application has been submitted successfully!',
                'data' => $submission,
            ], 201);

        } catch (\Exception $e) {
            Log::error('Career Submission Error: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while submitting your application. Please try again.',
            ], 500);
        }
    }

    private function sendNotificationEmail(CareerSubmission $submission)
    {
        $adminEmailSetting = Setting::where('key', 'email_notification_admin')->first();
        $adminEmail = $adminEmailSetting ? $adminEmailSetting->value : config('mail.from.address');

        if (! $adminEmail) {
            return;
        }

        $jobTitle = $submission->career ? $submission->career->title : 'Unknown Position';

        $emailBody = implode("\n", [
            'New Job Application Received',
            '============================',
            '',
            'Position: '.$jobTitle,
            '',
            'Applicant Details:',
            'Name: '.$submission->full_name,
            'Email: '.$submission->email,
            'Phone: '.$submission->mobile_phone,
            '',
            'Message:',
            '---------',
            $submission->message ?? 'No message provided.',
            '---------',
            '',
            'Resume is attached to this email.',
        ]);

        try {
            Mail::raw($emailBody, function ($m) use ($adminEmail, $submission, $jobTitle) {
                $m->to($adminEmail)
                    ->replyTo($submission->email, $submission->full_name)
                    ->subject('Application for '.$jobTitle.': '.$submission->full_name);

                if ($submission->resume_path) {
                    $filePath = storage_path('app/public/'.$submission->resume_path);
                    if (file_exists($filePath)) {
                        $m->attach($filePath);
                    }
                }
            });
        } catch (\Throwable $e) {
            Log::error('Failed to send career notification email: '.$e->getMessage());
        }
    }
}
