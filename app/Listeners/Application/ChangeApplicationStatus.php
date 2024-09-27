<?php

namespace App\Listeners\Application;

use App\Events\Application\ApplicationApproved;
use App\Events\Application\ApplicationDisapproved;
use App\Events\Application\ApplicationVoted;
use App\Models\Vote;

class ChangeApplicationStatus
{
    /**
     * Handle the event.
     *
     * @param  ApplicationVoted  $event
     * @return void
     */
    public function handle(ApplicationVoted $event)
    {
        $application = $event->application;
        $result = $application->isVoteMajority();

        if ($result) {
            $application->update([
                'status' => $result,
                'disapproved_remarks' => $result == Vote::APPROVED ? null : 'Disapproved by majority',
                'disapproved_date' => $result == Vote::APPROVED ? null : now(),
                'posted_date' => $result == Vote::APPROVED ? now() : null,
            ]);

            if ($result == Vote::APPROVED) {
                event(new ApplicationApproved($application));
            } else {
                event(new ApplicationDisapproved($application));
            }
        }
    }
}
