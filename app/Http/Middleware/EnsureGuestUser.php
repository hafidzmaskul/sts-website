<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Events\Registered;

class EnsureGuestUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            return $next($request);
        }

        // Create a new guest user if not authenticated
        // Logic adapted from App\Http\Controllers\Api\GuestController
        $user = DB::transaction(function () {
            $uuid = Str::uuid()->toString();
            $email = "guest_{$uuid}@example.com";
            $password = Hash::make(Str::random(32));

            $user = User::create([
                'name' => 'Guest User',
                'email' => $email,
                'password' => $password,
            ]);

            $user->assignRole('guest');

            Customer::create([
                'user_id' => $user->id,
                'first_name' => 'Guest',
                'last_name' => 'User',
                'email' => $email,
                'role_applied' => 'guest',
                'status' => 'active',
                'status_review' => 'approved',
            ]);

            event(new Registered($user));

            return $user;
        });

        Auth::login($user);

        return $next($request);
    }
}
