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

        return app(\Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class)->handle($request, $next);
    }
}
