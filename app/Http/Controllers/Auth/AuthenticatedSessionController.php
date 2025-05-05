<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;


class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): RedirectResponse
{
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        $user = Auth::user();
        if ($user && $user->role === 'admin') {
            Cache::put('admin_online', true, now()->addMinutes(5)); // Set admin online status
        }

        // Redirect to the desired page (e.g., homepage)
        return redirect()->route('welcome');
    }

    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ]);
}

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
{
    $user = Auth::user();
    if ($user && $user->role === 'admin') {
        Cache::forget('admin_online'); // Clear admin status
    }

    Auth::guard('web')->logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/');
}


    public function createPublisherLoginForm()
{
    return view('auth.login-publisher');
}

public function storePublisherLogin(Request $request)
{
    $credentials = $request->only('email', 'password');
    
    if (Auth::attempt($credentials)) {
        if (Auth::user()->role == 'publisher') {
            return redirect()->route('publisher.create'); // Redirect to the publisher dashboard or upload page
        } else {
            Auth::logout(); // If logged-in user is not a publisher, log them out
            return redirect()->route('login.publisher.form')->withErrors([
                'email' => 'Only publishers can log in here.',
            ]);
        }
    }

    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ]);
}

protected function authenticated(Request $request, $user)
{
    if ($user->role === 'admin') {
        $user->last_login_at = now();
        $user->save();
    }
}

}
