<?php

namespace App\Listeners\Application;

use App\Events\Application\ApplicationDisapproved;
use App\Jobs\SendNotificationOnUserForDisapprovedApplication;
use App\Models\User;

class NotifyUserOnDisapprovedApplication
{
    /**
     * Handle the event.
     *
     * @param  ApplicationDisapproved  $event
     * @return void
     */
    public function handle(ApplicationDisapproved $event)
    {
        $restrictedUsers = User::restrictedUser()->doesntHave('permissions', 'or')->pluck('id');
        $users = User::whereNotIn('id', $restrictedUsers)->get();
        $users->each(function ($user, $key) use ($event) {
            SendNotificationOnUserForDisapprovedApplication::dispatch($user, $event->application);
        });
        SendNotificationOnUserForDisapprovedApplication::dispatch($event->application->user, $event->application);
    }
}
