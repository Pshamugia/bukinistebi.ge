<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Check if the user is logged in
        if (!Auth::check()) {
            // If not logged in, redirect to login page
            return redirect()->route('login')->with('error', 'Please log in to access the admin panel.');
        }

        // Check if the logged-in user is an admin
        if (Auth::user()->role === 'admin') {
            return $next($request); // Allow admin users to proceed
        }

        // If not an admin, redirect to the home page
        return redirect('/')->with('error', 'Unauthorized access.');
    }
}
