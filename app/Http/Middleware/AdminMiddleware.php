<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Debugging: Check if the token is present
        if (!$request->bearerToken()) {
            return response()->json(['message' => 'Token not provided.'], 401);
        }

        // Debugging: Check the authenticated user
        if (!Auth::check()) {
            return response()->json(['message' => 'User not authenticated.'], 401);
        }

        // Check if the user is an admin
        if (Auth::user()->role === 'admin') {
            return $next($request);
        }

        return response()->json(['message' => 'Access denied. Admins only.'], 403);
    }
}
