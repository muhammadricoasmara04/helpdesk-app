<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    Log::info('User mencoba join channel', [
        'user_id' => $user?->id,
        'ticket_id' => $id,
    ]);
return true;
    // return (int) $user->id === (int) $id;
});
