<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Str;

class CustomerRegistrationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'account' => ['required', 'string', 'max:255'],
            'job_title' => ['required', 'string', 'max:255'],
            'role' => ['required', 'string', 'in:trade account,credit facilities account'],
        ]);

        // Create Customer with pending status
        $customer = Customer::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'role_applied' => $request->role,
            'account_number' => $request->account,
            'job_title' => $request->job_title,
            'status' => 'active', // System status active, but review status pending
            'status_review' => 'pending',
        ]);

        // Notify Admin
        // \Illuminate\Support\Facades\Notification::route('mail', 'gemaantikahr@gmail.com')
        //    ->notify(new \App\Notifications\NewCustomerSubmissionNotification($customer));
        // Using Facade directly for simplicity in this context
        try {
            \Illuminate\Support\Facades\Notification::route('mail', 'gemaantikahr@gmail.com')
                ->notify(new \App\Notifications\NewCustomerSubmissionNotification($customer));
        } catch (\Exception $e) {
            // Log error or ignore if mail fails in local dev
        }

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Registration submitted successfully. Please wait for admin approval.',
                'customer' => $customer,
            ], 201);
        }

        return redirect()->back()->with('success', 'Registration submitted successfully. Please wait for admin approval.');
    }
}
