<?php

use Illuminate\Support\Facades\Broadcast;

// This defines the authorization logic for any channel that matches the pattern `chat.{userId}`.
// When your frontend tries to listen on "chat.3", this code will run.
Broadcast::channel('chat.{userId}', function ($user, $userId) {
    // It checks if the currently authenticated user's ID matches the ID from the channel name.
    // This is the security check.
    return (int) $user->id === (int) $userId;
});


 