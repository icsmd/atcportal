<?php

namespace App\Listeners\Application;

use App\Events\Application\ApplicationVoted;
use App\Jobs\SendVoteNotification;
use App\Models\User;

class NotifyAgencyOnVoting
{
    /**
     * Handle the event.
     *
     * @param  ApplicationVoted  $event
     * @return void
     */
    public function handle(ApplicationVoted $event)
    {
        $users = User::viewVoteUser()->where('id', '<>', auth()->user()->id)->get();
        $users->each(function ($user, $key) use ($event) {
            SendVoteNotification::dispatch($user, $event->vote);
        });
    }
}
