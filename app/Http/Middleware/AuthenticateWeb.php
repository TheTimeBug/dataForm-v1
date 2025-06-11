<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateWeb
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // For web routes, we can't directly check localStorage
        // We'll let the frontend handle authentication and redirect to unauthorized if needed
        // This middleware will be bypassed for now, and we'll rely on JavaScript checks
        
        return $next($request);
    }
} 