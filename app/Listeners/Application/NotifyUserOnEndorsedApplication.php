<?php

namespace App\Listeners\Application;

use App\Events\Application\ApplicationEndorsed;
use App\Jobs\SendNotificationOnUserForEndorsedApplication;
use App\Models\User;

class NotifyUserOnEndorsedApplication
{
    /**
     * Handle the event.
     *
     * @param  ApplicationEndorsed  $event
     * @return void
     */
    public function handle(ApplicationEndorsed $event)
    {
        $restrictedUsers = User::restrictedUser()->doesntHave('permissions', 'or')->pluck('id');
        $users = User::whereNotIn('id', $restrictedUsers)->get();
        $users->each(function ($user, $key) use ($event) {
            SendNotificationOnUserForEndorsedApplication::dispatch($user, $event->application);
        });
        SendNotificationOnUserForEndorsedApplication::dispatch($event->application->user, $event->application);
    }
}
