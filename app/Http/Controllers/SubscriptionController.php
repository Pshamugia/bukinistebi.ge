<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subscriber;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SubscriptionController extends Controller
{
    public function subscribe(Request $request)
{
    $disposableDomains = [
        'dont-reply.me', 'mailinator.com', 'tempmail.com', 'yopmail.com',
        'trashmail.com', 'guerrillamail.com', '10minutemail.com', 'fakeinbox.com'
    ];

    $validator = Validator::make($request->all(), [
        'email' => [
            'required',
            'email',
            'unique:subscribers,email',
            function ($attribute, $value, $fail) use ($disposableDomains) {
                $domain = strtolower(substr(strrchr($value, "@"), 1));
                if (in_array($domain, $disposableDomains)) {
                    $fail('დროებითი ელფოსტები არ არის დაშვებული.');
                }
            },
        ],
    ], [
        'email.required' => 'სწორად ჩაწერე ელფოსტა.',
        'email.email' => 'ჩაწერე არსებული ელფოსტა.',
        'email.unique' => 'ეს ელფოსტა უკვე რეგისტრირებულია ჩვენს ბაზაში.',
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    Subscriber::create([
        'email' => $request->email,
    ]);

    return redirect()->back()->with('subscription_success', 'მადლობა გამოწერისთვის!');
}

public function subscribers()
{
    $subscribers = Subscriber::all();
    return view('admin.subscribers', compact('subscribers'));
}
}
