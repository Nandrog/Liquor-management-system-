<?php  
namespace App\Modules\Communications\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\MessageSent;
use App\Notifications\NewMessageNotification;

class MessageController extends Controller
{
    public function index()
{
     $user = auth::user();
    $currentUser = Auth::user();

    // Get all unique user IDs the current user has had a conversation with
    $userIds = Message::where('sender_id', $currentUser->id)
        ->orWhere('receiver_id', $currentUser->id)
        ->get(['sender_id', 'receiver_id'])
        ->flatMap(function ($message) use ($currentUser) {
            return [$message->sender_id, $message->receiver_id];
        })
        ->unique()
        ->reject(function ($id) use ($currentUser) {
            return $id == $currentUser->id; // Remove our own ID from the list
        });

    $conversations = User::whereIn('id', $userIds)->get();
     

    // The view path must specify the module using '::' syntax
    return view('communications::messages.index', compact('conversations','user'));
}

    public function store(Request $request)
    {
    
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'context' => 'required|string',
        ]);

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'context' => $request->context,
        ]);

        $receiver = User::find($request->receiver_id);

        //  send notification
        if ($receiver) {
            $receiver->notify(new NewMessageNotification($message));
        }

        // Fire event for real-time broadcasting
        broadcast(new MessageSent($message))->toOthers();

        return response()->json(['status' => 'Message sent']);
    }

    public function markAsRead($id)
    {
        $message = Message::where('id', $id)
           ->where('receiver_id', Auth::id())
            ->firstOrFail();

        $message->is_read = true;
        $message->save();

        return response()->json(['status' => 'Message marked as read']);
    }
    // app/Modules/Communications/Http/Controllers/MessageController.php

// ... inside the MessageController class ...

public function show(User $user)
{
    $currentUserId = Auth::id();
    $contactId = $user->id;

    // Mark messages from this user as read
    Message::where('sender_id', $contactId)
        ->where('receiver_id', $currentUserId)
        ->where('is_read', false)
        ->update(['is_read' => true]);

    $messages = Message::where(function ($query) use ($currentUserId, $contactId) {
            $query->where('sender_id', $currentUserId)->where('receiver_id', $contactId);
        })->orWhere(function ($query) use ($currentUserId, $contactId) {
            $query->where('sender_id', $contactId)->where('receiver_id', $currentUserId);
        })
        ->orderBy('created_at')
        ->get();

    return response()->json(['messages' => $messages]);
}


public function destroy($id)
{
    $message = Message::findOrFail($id);

    // Optional: Only allow sender to delete their message
   if ($message->sender_id !== Auth::id()) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    $message->delete();

    return response()->json(['success' => true]);
}

}
