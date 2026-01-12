<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
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
        if ($request->bearerToken()) {
            return app(\Illuminate\Auth\Middleware\Authenticate::class)->handle($request, $next, 'sanctum');
        }

        // Don't trigger the browser's native Basic Auth dialog for API requests.
        // Return a JSON 401 response instead of calling AuthenticateWithBasicAuth,
        // which emits a WWW-Authenticate header and causes the native prompt.
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        return app(\Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class)->handle($request, $next);
    }
}
