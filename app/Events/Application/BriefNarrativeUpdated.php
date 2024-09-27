<?php

namespace App\Events\Application;

use App\Models\Application;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BriefNarrativeUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var string
     */
    public $narrative;

    /**
     * @var Application
     */
    public $application;

    /**
     * Create a new event instance.
     *
     * @param  Application  $application
     * @param  string  $narrative
     */
    public function __construct(Application $application, $narrative)
    {
        $this->application = $application;
        $this->narrative = $narrative;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
