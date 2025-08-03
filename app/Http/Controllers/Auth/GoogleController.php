<?php


namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        // ✅ Store the previous page user was on (so we can redirect back)
        if (!Session::has('url.intended')) {
            Session::put('url.intended', url()->previous());
        }

        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            // ✅ Check if user exists
            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                // ✅ Create new user
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'password' => bcrypt(Str::random(16)), // Random password
                ]);
            }

            // ✅ Log in the user
            Auth::login($user);

            // ✅ Redirect back to the page they came from (or home if not found)
            return redirect(Session::pull('url.intended', '/'));

        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Something went wrong with Google login.');
        }
    }
}
