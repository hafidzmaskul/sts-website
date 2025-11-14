<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactSubmissionController extends Controller
{
    /**
     * Store a newly created contact submission.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
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
            $submission = ContactSubmission::create($validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'Your message has been sent successfully!',
                'data' => $submission,
            ], 201); // Created

        } catch (\Exception $e) {
            // Handle potential database errors
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while sending your message. Please try again.',
            ], 500); // Internal Server Error
        }
    }
}