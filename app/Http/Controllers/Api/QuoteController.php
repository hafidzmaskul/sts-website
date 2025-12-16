<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Quote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class QuoteController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'country' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'project_details' => 'required|string',
            'marketing_opt_in' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $quote = Quote::create($request->all());

            // Send Email to Admin
            $adminEmail = 'gemaantikahr@gmail.com';
            $subject = 'New Quote Submission';
            $message = "You have received a new quote submission.\n\n" .
                "Name: {$quote->first_name} {$quote->last_name}\n" .
                "Company: {$quote->company_name}\n" .
                "Email: {$quote->email}\n" .
                "Phone: {$quote->phone}\n" .
                "Country: {$quote->country}\n" .
                "Project Details:\n{$quote->project_details}\n\n" .
                "View in Admin Panel: " . route('admin.quotes.show', $quote);

            Mail::raw($message, function ($mail) use ($adminEmail, $subject) {
                $mail->to($adminEmail)
                    ->subject($subject);
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Quote submitted successfully',
                'data' => $quote
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to submit quote',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
