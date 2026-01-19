<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class GuestController extends Controller
{
    /**
     * Handle a guest registration request.
     */
    public function store(Request $request)
    {
        return DB::transaction(function () use ($request) {
            // Generate random guest credentials
            $uuid = Str::uuid()->toString();
            $email = "guest_{$uuid}@example.com";
            $password = Hash::make(Str::random(32)); // Random strong password

            $user = User::create([
                'name' => 'Guest User',
                'email' => $email,
                'password' => $password,
            ]);

            $user->assignRole('guest');

            $customer = Customer::create([
                'user_id' => $user->id,
                'first_name' => 'Guest',
                'last_name' => 'User',
                'email' => $email,
                'role_applied' => 'guest',
                'status' => 'active',
                'status_review' => 'approved', // Auto-approve guests? logic says "no request body", so assuming minimal friction
            ]);

            event(new Registered($user));

            $deviceName = $request->header('User-Agent', 'Guest Device');
            $token = $user->createToken($deviceName)->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Guest registration successful',
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user->load('customer'),
            ], 201);
        });
    }
}
