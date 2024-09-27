<?php

namespace App\Events\Application;

use App\Models\Application;
use App\Models\MediaFile;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ApplicationUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $application;

    public $files;

    public $type;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Application $application, $files)
    {
        $this->application = $application;
        $this->files = $files;
        $this->type = MediaFile::SUPPORT_FILES;
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
