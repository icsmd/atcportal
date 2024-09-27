<?php

namespace App\Channels\Pinpoint;

use Aws\AwsClient;
use NotificationChannels\AwsPinpoint\Events\DeliveryFailed;
use NotificationChannels\AwsPinpoint\Events\DeliverySuccess;
use NotificationChannels\AwsPinpoint\Exceptions\CouldNotSendNotification;

class PinpointClient
{
    /**
     * @var AwsClient
     */
    protected $client;

    /**
     * Create a AwsPinpointClient instance.
     *
     * @param  AwsClient  $client
     */
    public function __construct(AwsClient $client = null)
    {
        $this->client = $client;
    }

    /**
     * Send the Message.
     *
     * @param  PinpointSmsMessage  $message
     *
     * @throws CouldNotSendNotification
     */
    public function send(PinpointSmsMessage $message)
    {
        try {
            $result = $this->client->sendMessages([
                'ApplicationId' => config('sms.pinpoint.application_id'),
                'MessageRequest' => [
                    'Addresses' => $message->recipients,
                    'MessageConfiguration' => [
                        'SMSMessage' => [
                            'Body' => $message->body,
                            'MessageType' => $message->messageType,
                            'SenderId' => config('sms.pinpoint.sender_id'),
                        ],
                    ],
                ],
            ]);

            $output = $result->get('MessageResponse');

            foreach ($output['Result'] as $number => $res) {
                if ($res['DeliveryStatus'] === 'SUCCESSFUL') {
                    // Trigger event for successful deliveries
                    // event(new DeliverySuccess($number, $message->body, $res['StatusMessage']));
                    continue;
                }
                \Log::debug($number);
                \Log::debug($message->body);
                \Log::debug($res['StatusMessage']);
                // Trigger event for failed deliveries
                // event(new DeliveryFailed($number, $message->body, $res['StatusMessage']));
            }
        } catch (Exception $exception) {
            \Log::debug($eexception->getMessage());

            throw GeneralException('There was a problem in sending sms.');
        }
    }
}
