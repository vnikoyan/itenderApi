<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

use Illuminate\Support\Facades\Log;
use App\Models\User\User;
use Google\Service\ServiceControl\Auth;
use Illuminate\Support\Facades\Broadcast;

// This is only for testing purposes
Broadcast::channel('testchannel', function ($user) {
    return true;
}); 

// This is probably closer to what most would use in production
Broadcast::channel('user.{id}', function ($user, $id) {
    //return true if api user is authenticated
    return (int) $user->id === (int) $id;
});