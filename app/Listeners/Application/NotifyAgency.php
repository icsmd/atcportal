<?php

namespace App\Listeners\Application;

use App\Events\Application\ApplicationSent;
use App\Jobs\SendNotificationOnUserForNewApplication;
use App\Models\Application;
use App\Models\User;

class NotifyAgency
{
    /**
     * Handle the event.
     *
     * @param  ApplicationSent  $event
     * @return void
     */
    public function handle(ApplicationSent $event)
    {
        if ($event->application->status == Application::DRAFT) {
            return;
        }

        $users = User::permission(config('atc.access.permission.approve_application'))->get();
        $users->each(function ($user, $key) use ($event) {
            SendNotificationOnUserForNewApplication::dispatch($user, $event->application);
        });
    }
}
