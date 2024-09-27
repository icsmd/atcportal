<?php

namespace App\Channels\Pinpoint;

use Illuminate\Notifications\Notification;

class PinpointChannel
{
    /**
     * @var \App\Channels\Pinpoint\PinpointClient
     */
    protected $client;

    /**
     * Create a AwsPinpointChannel instance.
     *
     * @param  \App\Channels\Pinpoint\PinpointClient  $client
     */
    public function __construct(PinpointClient $client)
    {
        $this->client = $client;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     */
    public function send($notifiable, Notification $notification)
    {
        if (! config('sms.pinpoint.status')) {
            return;
        }

        if (! $to = $notifiable->routeNotificationFor('sms')) {
            return;
        }

        $message = $notification->toSms($notifiable);

        if (is_string($message)) {
            $message = PinpointSmsMessage::create($message);
        }

        $message->setRecipients($to);
        $this->client->send($message);
    }
}
