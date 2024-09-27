<?php

namespace App\Listeners\Application;

use App\Events\Application\ApplicationApproved;
use App\Services\WordService;

class CreateResolution
{
    /**
     * @var WordService
     */
    protected $wordService;

    /**
     * ChangeApplicationStatus constructor.
     *
     * @param  WordService  $wordService
     */
    public function __construct(WordService $wordService)
    {
        $this->wordService = $wordService;
    }

    /**
     * Handle the event.
     *
     * @param  ApplicationApproved  $event
     * @return void
     */
    public function handle(ApplicationApproved $event)
    {
        $application = $event->application;

        $this->wordService->generateResolution($application);
    }
}
