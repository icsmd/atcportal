<?php

namespace App\Providers;

use App\Events\Application\ApplicationAccepted;
use App\Events\Application\ApplicationApproved;
use App\Events\Application\ApplicationDisapproved;
use App\Events\Application\ApplicationEndorsed;
use App\Events\Application\ApplicationSent;
use App\Events\Application\ApplicationUpdated;
use App\Events\Application\ApplicationVoted;
use App\Events\Application\BriefNarrativeUpdated;
use App\Events\Application\CommentPosted;
use App\Events\Application\ExtensionRequested;
use App\Events\Application\ResolutionUploaded;
use App\Listeners\Application\ChangeApplicationStatus;
use App\Listeners\Application\CreateBriefNarrativeHistory;
use App\Listeners\Application\CreateResolution;
use App\Listeners\Application\NotifyAgency;
use App\Listeners\Application\NotifyAgencyOnVoting;
use App\Listeners\Application\NotifyUserOnAcceptedApplication;
use App\Listeners\Application\NotifyUserOnComment;
use App\Listeners\Application\NotifyUserOnCompletedApplication;
use App\Listeners\Application\NotifyUserOnDisapprovedApplication;
use App\Listeners\Application\NotifyUserOnEndorsedApplication;
use App\Listeners\Application\NotifyUserOnExtensionRequestApplication;
use App\Listeners\Application\NotifyUserOnUploadedResolutionApplication;
use App\Listeners\Application\UploadSupportFiles;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        ApplicationSent::class => [
            NotifyAgency::class,
        ],
        CommentPosted::class => [
            NotifyUserOnComment::class,
        ],
        ApplicationVoted::class => [
            NotifyAgencyOnVoting::class,
            ChangeApplicationStatus::class,
        ],
        BriefNarrativeUpdated::class => [
            CreateBriefNarrativeHistory::class,
        ],
        ApplicationUpdated::class => [
            UploadSupportFiles::class,
        ],
        ApplicationAccepted::class => [
            NotifyUserOnAcceptedApplication::class,
        ],
        ApplicationEndorsed::class => [
            NotifyUserOnEndorsedApplication::class,
        ],
        ApplicationDisapproved::class => [
            NotifyUserOnDisapprovedApplication::class,
        ],
        ApplicationApproved::class => [
            CreateResolution::class,
            NotifyUserOnUploadedResolutionApplication::class,
        ],
        ResolutionUploaded::class => [
            NotifyUserOnCompletedApplication::class,
        ],
        ExtensionRequested::class => [
            NotifyUserOnExtensionRequestApplication::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
