<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
  public function users()
{
    $authId = Auth::id();

    // Get all users except the logged-in one
    $allOtherUsers = User::where('id', '!=', $authId)->get();

    // Get user IDs you've had conversations with
    $conversationUserIds = Message::select(
        DB::raw("CASE 
                    WHEN sender_id = $authId THEN receiver_id 
                    ELSE sender_id 
                 END AS user_id")
    )
    ->where('sender_id', $authId)
    ->orWhere('receiver_id', $authId)
    ->groupBy('user_id')
    ->pluck('user_id');

    // Get users with whom you've chatted
    $conversations = User::whereIn('id', $conversationUserIds)
        ->get()
        ->map(function ($u) use ($authId) {
            $u->last_message = Message::where(function ($query) use ($authId, $u) {
                $query->where('sender_id', $authId)->where('receiver_id', $u->id);
            })->orWhere(function ($query) use ($authId, $u) {
                $query->where('sender_id', $u->id)->where('receiver_id', $authId);
            })->latest()->value('message');
            return $u;
        });

    // Users you haven't chatted with yet
    $newChatUsers = User::where('id', '!=', $authId)
        ->whereNotIn('id', $conversationUserIds)
        ->get();

    return view('Messages.chat', [
        'users' => $allOtherUsers,
        'conversations' => $conversations,
        'newChatUsers' => $newChatUsers,
        'user' => null,         // No active chat selected yet
        'messages' => collect() // Empty message list
    ]);
}

public function chat(User $user)
{
    $authId = Auth::id();

    // Users with previous conversations
    $conversationUserIds = Message::select(
        DB::raw("CASE 
                    WHEN sender_id = $authId THEN receiver_id 
                    ELSE sender_id 
                 END AS user_id")
    )
    ->where('sender_id', $authId)
    ->orWhere('receiver_id', $authId)
    ->groupBy('user_id')
    ->pluck('user_id');

    // Users you've chatted with
    $conversations = User::whereIn('id', $conversationUserIds)
        ->get()
        ->map(function ($u) use ($authId) {
            $u->last_message = Message::where(function ($query) use ($authId, $u) {
                $query->where('sender_id', $authId)->where('receiver_id', $u->id);
            })->orWhere(function ($query) use ($authId, $u) {
                $query->where('sender_id', $u->id)->where('receiver_id', $authId);
            })->latest()->value('message');
            return $u;
        });

    // Users you haven’t chatted with
    $newChatUsers = User::where('id', '!=', $authId)
        ->whereNotIn('id', $conversationUserIds)
        ->get();

    // All users (for the "Available Users" list)
    $users = User::where('id', '!=', $authId)->get();

    // Get messages between logged in user and selected user
    $messages = Message::where(function ($query) use ($user, $authId) {
        $query->where('sender_id', $authId)->where('receiver_id', $user->id);
    })->orWhere(function ($query) use ($user, $authId) {
        $query->where('sender_id', $user->id)->where('receiver_id', $authId);
    })->orderBy('created_at')->get();

    return view('Messages.chat', [
        'user' => $user,
        'messages' => $messages,
        'conversations' => $conversations,
        'newChatUsers' => $newChatUsers,
        'users' => $users, // ✅ now passed to the blade
    ]);
}



    public function sendMessage(Request $request, User $user)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $user->id,
            'message' => $request->message,
        ]);

        return redirect()->route('chat.with', $user->id);
    }  
    
  //
}



