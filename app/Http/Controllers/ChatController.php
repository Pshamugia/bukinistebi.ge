<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Message;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $user = Auth::user();
    
        // Fetch chats for the authenticated user (user role) or admin
        $chats = Chat::with(['messages', 'user', 'admin'])
            ->where(function ($query) use ($user) {
                if ($user->role === 'user') {
                    $query->where('user_id', $user->id);
                } elseif ($user->role === 'admin') {
                    $query->where('admin_id', $user->id);
                }
            })
            ->get();
    
        return response()->json(['chats' => $chats]);
    }
    




    public function store(Request $request)
{
    $user = Auth::user(); // Sender
    $messageContent = $request->input('message');

    // For users, find or create a chat with the admin
    if ($user->role === 'user') {
        $chat = Chat::firstOrCreate(
            ['user_id' => $user->id, 'status' => 'active'],
            ['admin_id' => User::where('role', 'admin')->first()->id] // Dynamically assign admin ID
        );
    } else {
        // For admin, fetch the active chat with the specific user
        $chat = Chat::where('admin_id', $user->id)
            ->where('status', 'active')
            ->latest()
            ->first();

        if (!$chat) {
            return response()->json(['error' => 'No active chat found'], 404);
        }
    }

    // Store the message
    $chat->messages()->create([
        'sender_id' => $user->id,
        'message' => $messageContent,
    ]);

    return response()->json(['status' => 'Message sent successfully']);
}

 
}