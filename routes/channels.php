<?php

use Illuminate\Support\Facades\Broadcast;

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

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
// Broadcast::channel('chats', function ($user) {
//     return auth()->check();
// });
Broadcast::channel('chats', function ($user) {
    return $user;
});
Broadcast::channel('fence', function ($user) {
    return $user;
});
Broadcast::channel('uploadVideoCh', function ($user) {
    return auth()->check();
});
Broadcast::channel('livelecture', function ($user) {
    return auth()->check();
});
Broadcast::channel('bulletin', function ($user) {
    return auth()->check();
});
Broadcast::channel('group', function ($user) {
    return auth()->check();
});
// Broadcast::channel('chats', function ($user, $id) {
//     return true;
// });
