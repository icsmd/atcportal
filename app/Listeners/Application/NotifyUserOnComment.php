<?php

namespace App\Listeners\Application;

use App\Events\Application\CommentPosted;
use App\Jobs\SendCommentNotification;
use App\Models\User;

class NotifyUserOnComment
{
    /**
     * Handle the event.
     *
     * @param  CommentPosted  $event
     * @return void
     */
    public function handle(CommentPosted $event)
    {
        $users = User::viewDiscussionUser()->where('id', '<>', auth()->user()->id)->get();
        $users->each(function ($user, $key) use ($event) {
            SendCommentNotification::dispatch($user, $event->comment);
        });
    }
}
