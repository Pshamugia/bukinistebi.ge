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
        // Allow admins AND sub-admins into admin panel
        if (in_array(Auth::user()->role, ['admin', 'subadmin'])) {
            return $next($request);
        }

        // Block everyone else
        abort(403);
        // If not an admin, redirect to the home page
        return redirect('/')->with('error', 'Unauthorized access.');
    }
}
