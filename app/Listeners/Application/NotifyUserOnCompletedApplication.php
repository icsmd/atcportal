<?php

namespace App\Listeners\Application;

use App\Events\Application\ResolutionUploaded;
use App\Jobs\SendNotificationOnUserForCompletedApplication;
use App\Models\User;

class NotifyUserOnCompletedApplication
{
    /**
     * Handle the event.
     *
     * @param  ResolutionUploaded  $event
     * @return void
     */
    public function handle(ResolutionUploaded $event)
    {
        $restrictedUsers = User::restrictedUser()->doesntHave('permissions', 'or')->pluck('id');
        $users = User::whereNotIn('id', $restrictedUsers)->get();
        $users->each(function ($user, $key) use ($event) {
            SendNotificationOnUserForCompletedApplication::dispatch($user, $event->application);
        });
        SendNotificationOnUserForCompletedApplication::dispatch($event->application->user, $event->application);
    }
}
