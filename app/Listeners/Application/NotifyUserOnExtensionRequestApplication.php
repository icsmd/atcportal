<?php

namespace App\Listeners\Application;

use App\Events\Application\ExtensionRequested;
use App\Jobs\SendNotificationOnUserForExtensionApplication;

class NotifyUserOnExtensionRequestApplication
{
    /**
     * Handle the event.
     *
     * @param  ExtensionRequested  $event
     * @return void
     */
    public function handle(ExtensionRequested $event)
    {
        SendNotificationOnUserForExtensionApplication::dispatch($event->application);
    }
}
