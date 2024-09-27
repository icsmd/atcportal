<?php

return [

    /*
    |--------------------------------------------------------------------------
    | SMS Configurations
    |--------------------------------------------------------------------------
    |
    |
    */

    'pinpoint' => [
        'status' => env('AWS_PINPOINT', false),
        'sender_id' => env('AWS_PINPOINT_SENDER_ID', 'ATC Portal'),
        'application_id' => env('AWS_PINPOINT_APPLICATION_ID'),
        'access_key' => env('AWS_PINPOINT_ACCESS_KEY_ID'),
        'secret_key' => env('AWS_PINPOINT_SECRET_ACCESS_KEY'),
        'default_region' => env('AWS_PINPOINT_DEFAULT_REGION', 'ap-southeast-1'),
    ],

];
