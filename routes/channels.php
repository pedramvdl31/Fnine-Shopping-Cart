<?php

use Illuminate\Support\Facades\Auth;

Broadcast::channel('private-user.{userId}', function ($user, $userId) {
    // Ensure that the authenticated user is only subscribing to their own channel
    return (int) Auth::id() === (int) $userId;
});
