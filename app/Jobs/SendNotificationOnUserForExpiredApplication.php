<?php

namespace App\Jobs;

use App\Models\Application;
use App\Models\User;
use App\Notifications\ApplicationExpired;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendNotificationOnUserForExpiredApplication implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $application;

    public $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, Application $application)
    {
        $this->user = $user;
        $this->application = $application;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->application->update([
            'expiration_notified' => true,
            'status' => Application::EXPIRED,
        ]);
        $this->user->notify(new ApplicationExpired($this->application));
    }
}
