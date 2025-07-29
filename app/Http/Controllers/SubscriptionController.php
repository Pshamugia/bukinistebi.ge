<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use App\Mail\SubscriptionNotification;
use Illuminate\Support\Facades\Validator; 
use Illuminate\Http\RedirectResponse;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    public function subscribe(Request $request)
{
    $disposableDomains = [
        'dont-reply.me', 'testform.xyz', 'mailinator.com', 'tempmail.com', 'yopmail.com',
        'trashmail.com', 'guerrillamail.com', '10minutemail.com', 'fakeinbox.com', 'formtest.guru', 'mailbox.in.ua'
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




public function subscribers(Request $request)
{
    $subscribers = \App\Models\Subscriber::all();

    $range = $request->query('range', 'all');

    $dateFilter = null;
    if ($range === '24h') {
        $dateFilter = now()->subDay();
    } elseif ($range === '7d') {
        $dateFilter = now()->subDays(7);
    } elseif ($range === '30d') {
        $dateFilter = now()->subDays(30);
    }

    $queuedQuery = DB::table('jobs');
    $failedQuery = DB::table('failed_jobs');
    $openedQuery = DB::table('email_logs')->whereNotNull('opened_at');

    if ($dateFilter) {
        $queuedQuery->where('created_at', '>=', $dateFilter);
        $failedQuery->where('failed_at', '>=', $dateFilter); // Check if your failed_jobs table has 'failed_at' column!
        $openedQuery->where('opened_at', '>=', $dateFilter);
    }

    $queued = $queuedQuery->count();
    $failed = $failedQuery->count();
    $opened = $openedQuery->count();

    return view('admin.subscribers', compact('subscribers', 'queued', 'failed', 'opened', 'range'));
}


 
public function sendEmailToSubscribers(Request $request)
{
    $request->validate([
        'emails' => 'required|array',
        'emails.*' => 'email',
        'custom_subject' => 'nullable|string|max:255',
        'custom_message' => 'nullable|string|max:10000',
    ]);

    $subjectLine = $request->filled('custom_subject') 
        ? $request->custom_subject 
        : 'ახალი წიგნი bukinistebi.ge-ზე';

    $messageContent = $request->filled('custom_message')
        ? $request->custom_message
        : "ბუკინისტებზე დაემატა ახალი წიგნი. გვეწვიე საიტზე: bukinistebi.ge!";

    Log::info('Queueing emails to: ', $request->emails);

    foreach ($request->emails as $email) {
        try {
            $encryptedEmail = Crypt::encrypt($email);
    
            Mail::to($email)->queue(new SubscriptionNotification(
                $subjectLine,
                $messageContent,
                $encryptedEmail
            ));
    
            \App\Models\EmailLog::updateOrCreate(
                ['email' => $email],
                ['sent_at' => now()]
            );
    
        } catch (\Throwable $e) {
            Log::error("Queueing failed for: $email — " . $e->getMessage());
    
            \App\Models\EmailLog::updateOrCreate(
                ['email' => $email],
                ['sent_at' => null]
            );
        }
    }

    return redirect()->back()->with('success', 'ელფოსტები წარმატებით დაემატა რიგში გასაგზავნად.');
}



public function subscribeAllUsers(): RedirectResponse
    {
        // Get all user emails
        $users = User::pluck('email')->toArray();

        // Get all existing subscriber emails
        $existingSubscribers = Subscriber::pluck('email')->toArray();

        // Calculate users who are not yet subscribers
        $newSubscribers = array_diff($users, $existingSubscribers);

        // Add each new email to subscribers
        foreach ($newSubscribers as $email) {
            Subscriber::create(['email' => $email]);
        }

        return redirect()->back()->with('success', 'ყველა მომხმარებელი, რომელიც არ იყო გამომწერი, დაემატა წარმატებით.');
    }



public function unsubscribe($encryptedEmail)
{
    try {
        $email = Crypt::decrypt($encryptedEmail);
        $subscriber = Subscriber::where('email', $email)->first();

        if ($subscriber) {
            $subscriber->delete();
            return view('emails.unsubscribe_success');
        } else {
            return view('emails.unsubscribe_error', ['message' => 'ელფოსტა ვერ მოიძებნა.']);
        }
    } catch (\Exception $e) {
        return view('emails.unsubscribe_error', ['message' => 'ბმული არასწორია ან ვადაგასულია.']);
    }
}



public function destroy($id)
{
    $subscriber = \App\Models\Subscriber::find($id);

    if (!$subscriber) {
        return redirect()->back()->with('error', 'გამომწერი ვერ მოიძებნა');
    }

    $subscriber->delete();

    return redirect()->back()->with('success', 'გამომწერი წარმატებით წაიშალა');
}



public function emailStats()
{
    $queued = DB::table('jobs')->count();
    $failed = DB::table('failed_jobs')->count();
    $opened = DB::table('email_logs')->whereNotNull('opened_at')->count();

    return view('admin.email_stats', compact('queued', 'failed', 'opened'));
}



}
