<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subscriber;

class SubscriptionController extends Controller
{
    public function subscribe(Request $request)
    {
        // Validate the email input with the correct table name
        $request->validate([
            'email' => 'required|email|unique:subscribers,email', // Correct table: subscribers
        ], [
            'email.required' => 'სწორად ჩაწერე ელფოსტა.',
            'email.email' => 'ჩაწერე არსებული ელფოსტა.',
            'email.unique' => 'ეს ელფოსტა უკვე რეგისტრირებულია ჩვენს ბაზაში.',
        ]);

        // Store the email in the database
        Subscriber::create([
            'email' => $request->email,
        ]);

        // Redirect back with a success message
        return redirect()->back()->with('subscription_success', 'მადლობა გამოწერისთვის!');
    }


    public function subscribers()
    {
$subscribers = Subscriber::all();

        return view('admin.subscribers', compact('subscribers'));
    }
}
