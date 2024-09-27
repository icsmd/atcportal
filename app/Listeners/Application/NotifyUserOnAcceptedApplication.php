<?php

namespace App\Listeners\Application;

use App\Events\Application\ApplicationAccepted;
use App\Jobs\SendNotificationOnUserForAcceptedApplication;
use App\Models\User;

class NotifyUserOnAcceptedApplication
{
    /**
     * Handle the event.
     *
     * @param  ApplicationAccepted  $event
     * @return void
     */
    public function handle(ApplicationAccepted $event)
    {
        $restrictedUsers = User::restrictedUser()->doesntHave('permissions', 'or')->pluck('id');
        $users = User::whereNotIn('id', $restrictedUsers)->get();
        $users->each(function ($user, $key) use ($event) {
            SendNotificationOnUserForAcceptedApplication::dispatch($user, $event->application);
        });
        SendNotificationOnUserForAcceptedApplication::dispatch($event->application->user, $event->application);
    }
}
