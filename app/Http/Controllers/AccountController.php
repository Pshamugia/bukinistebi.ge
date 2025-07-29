<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; // Add this line
use App\Models\User; // Ensure you're importing the User model


class AccountController extends Controller
{
    public function edit()
    {
        // Retrieve the currently authenticated user
        $user = Auth::user();
        return view('account.edit', compact('user'));
    }

    public function update(Request $request)
    {
        // Validate the form input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'nullable|string|min:8|confirmed', // Password is optional
        ]);

        // Retrieve the currently authenticated user
        $user = Auth::user();

        // Update the user's name and email
        $user->name = $request->name;
        $user->email = $request->email;

        // If the password field is filled, update the password
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password); // Hash password before saving
        }

        // Save the changes
        $user->save();

        // Redirect back with success message
        return redirect()->route('account.edit')->with('success', 'შენი პროფილი წარმატებით განახლდა.');
    }
}
