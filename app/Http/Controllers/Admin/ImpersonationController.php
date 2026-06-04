<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ImpersonationController extends Controller
{
    public function masquerade($userId)
    {
        $user = User::findOrFail($userId);

        Auth::login($user);

        return redirect()->route('home');
    }
}
