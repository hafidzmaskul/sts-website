<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ImpersonationController extends Controller
{
    public function masquerade($userId)
    {
        $user = User::findOrFail($userId);

        Auth::login($user);

        return redirect()->route('home');
    }
}
