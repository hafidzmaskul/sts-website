<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SanctumOrBasic
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if already authenticated (e.g. via session or actingAs in tests)
        if (Auth::check()) {
            return $next($request);
        }

        if ($request->bearerToken()) {
            return app(\Illuminate\Auth\Middleware\Authenticate::class)->handle($request, $next, 'sanctum');
        }

        // Attempt Basic Auth
        if (Auth::onceBasic() === null) {
            return $next($request);
        }

        // If both fail, return JSON 401 without WWW-Authenticate header to avoid browser prompt
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        return app(\Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class)->handle($request, $next);
    }
}
