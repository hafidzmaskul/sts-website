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
        ]);

        $user = User::create([
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'password' => Hash::make(Str::random(10)), // Generate random password
        ]);

        $user->assignRole('customer');

        Customer::create([
            'user_id' => $user->id,
            'account_number' => $request->account,
            'job_title' => $request->job_title,
            'status' => 'active',
        ]);

        event(new Registered($user));

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Registration submitted successfully.',
                'user' => $user,
            ], 201);
        }

        // We could log the user in, or just redirect with success
        // Auth::login($user);

        return redirect()->back()->with('success', 'Registration submitted successfully. Please check your email.');
    }
}
