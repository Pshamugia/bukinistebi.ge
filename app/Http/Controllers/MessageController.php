<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'chat_id' => 'required|exists:chats,id',
            'message' => 'required|string',
        ]);

        $message = Message::create([
            'chat_id' => $request->chat_id,
            'sender_id' => Auth::id(),
            'message' => $request->message,
        ]);

        return response()->json($message);
    }

    public function getMessages($chatId)
    {
        $messages = Message::where('chat_id', $chatId)->with('sender')->get();
        return response()->json($messages);
    }
}
