<?php

namespace App\Providers;

use App\Channels\Pinpoint\PinpointChannel;
use App\Channels\Pinpoint\PinpointClient;
use Aws\Laravel\AwsFacade;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Notification::resolved(function (ChannelManager $service) {
            $service->extend('sms', function ($app) {
                return new PinpointChannel(
                    new PinpointClient(
                        AwsFacade::createClient(
                            'pinpoint',
                            [
                                'credentials' => [
                                    'key' => config('sms.pinpoint.access_key'),
                                    'secret' => config('sms.pinpoint.secret_key'),
                                ],
                                'region' => config('sms.pinpoint.default_region'),
                            ]
                        )
                    )
                );
            });
        });
    }
}
