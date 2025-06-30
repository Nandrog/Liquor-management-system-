<?php

namespace App\Notifications;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class NewMessageNotification extends Notification implements ShouldBroadcast
{
    use Queueable;

    public Message $message;

    /**
     * Create a new notification instance.
     */
    public function __construct(Message $message)
    {
        // When we create this notification, we store the message object
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // We want this notification to be stored in the database AND
        // broadcast over WebSockets for real-time UI updates (like a toast).
        return ['database', 'broadcast'];
    }

    /**
     * Get the array representation of the notification for the database.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        // This is the data that will be stored in the `notifications` table.
        return [
            'sender_id' => $this->message->sender->id,
            'sender_name' => $this->message->sender->name,
            'message_context' => \Illuminate\Support\Str::limit($this->message->context, 50) // A short preview
        ];
    }

    /**
     * Get the broadcastable representation of the notification.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        // This is the data that gets sent over Pusher/WebSockets.
        return new BroadcastMessage([
            'sender_name' => $this->message->sender->name,
            'message_context' => \Illuminate\Support\Str::limit($this->message->context, 50)
        ]);
    }
}