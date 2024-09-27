<?php

namespace App\Listeners\Application;

use App\Events\Application\BriefNarrativeUpdated;

class CreateBriefNarrativeHistory
{
    /**
     * Handle the event.
     *
     * @param  BriefNarrativeUpdated  $event
     * @return void
     */
    public function handle(BriefNarrativeUpdated $event)
    {
        $application = $event->application;
        $narrative = $event->narrative;

        $application->addNarrativeHistory($narrative);
    }
}
