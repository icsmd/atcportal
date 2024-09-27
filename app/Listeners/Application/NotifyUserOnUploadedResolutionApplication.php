<?php

namespace App\Listeners\Application;

use App\Events\Application\ApplicationApproved;
use App\Jobs\SendNotificationOnUserForFinalResolution;
use App\Models\User;

class NotifyUserOnUploadedResolutionApplication
{
    /**
     * Handle the event.
     *
     * @param  ApplicationApproved  $event
     * @return void
     */
    public function handle(ApplicationApproved $event)
    {
        $restrictedUsers = User::restrictedUser()->doesntHave('permissions', 'or')->pluck('id');
        $users = User::whereNotIn('id', $restrictedUsers)->get();
        $users->each(function ($user, $key) use ($event) {
            SendNotificationOnUserForFinalResolution::dispatch($user, $event->application);
        });
        SendNotificationOnUserForFinalResolution::dispatch($event->application->user, $event->application);
    }
}
