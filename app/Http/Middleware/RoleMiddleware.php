<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        Log::info('RoleMiddleware accessed. Role expected: ' . $role);

        // Check if user is authenticated
        if (!Auth::check()) {
            Log::info('User not authenticated, redirecting to publisher login.');
            return redirect()->route('login.publisher.form')->withErrors(['access' => 'You must be logged in.']);
        }

        // Check if user's role matches the required role
        if (Auth::user()->role !== $role) {
            Log::info('Access denied. User role: ' . Auth::user()->role . ', expected role: ' . $role);
            return redirect()->route('login.publisher.form')->withErrors(['access' => 'Access denied. Only publishers can access this page.']);
        }
        if (strtolower(Auth::user()->role) !== strtolower($role)) {
            Log::info('Access denied due to case sensitivity. User role: ' . Auth::user()->role . ' Expected role: ' . $role);
            return redirect()->route('login.publisher.form')->withErrors(['access' => 'Access denied. Only publishers can access this page.']);
        }

        Log::info('User authorized as ' . $role . '. Continuing to next request.');
        return $next($request);
    }
}
