<?php

use App\Models\Application;
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

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('update.{application_id}', function ($user, $application_id) {

    if ($user->hasPermissionTo(config('atc.access.permission.restrict_view'))) {
        return false;
    }

    $application = Application::find($application_id);

    if (empty($application)) {
        return false;
    }

    return true;
});
