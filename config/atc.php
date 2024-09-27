<?php

/*
 * All configuration options for ATC
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Access
    |--------------------------------------------------------------------------
    |
    | Configurations related to the ATC access/authorization options
    */
    'access' => [
        'majority_count' => env('NUMBER_OF_MAJORITY_VOTES', 1),

        /**
         * Days remaining for the application to request extension
         */
        'days_remaining' => env('DAYS_REMAIN_EXTENSION', 3),

        /**
         * max days of detention
         */
        'days_of_detention' => env('DAYS_OF_DETENTION', 14),

        /**
         * max days of detention extension
         */
        'days_of_detention_extension' => env('DAYS_OF_DETENTION_EXTENSION', 10),

        /**
         * Hours before the application expires
         */
        'hours_remaining' => env('HOURS_REMAIN_EXPIRATION', 36),

        /**
         * QR Url when scanned
         */
        'qr_url' => env('QR_URL', 'http://localhost/qrcode/'),

        /**
         * Resolution document Path for 10 days
         */
        'resolution_10_days' => env('RESOLUTION_10_DAYS'),

        /**
         * Resolution document Path for 14 days
         */
        'resolution_14_days' => env('RESOLUTION_14_DAYS'),

        'role' => [

            /*
             * The name of the administrator role
             * Should be Administrator by design and unable to change from the backend
             * It is not recommended to change
             */
            'admin' => 'Administrator',

            /*
             * The name of the Applicant role
             * Displays all own applications and can send application
             */
            'applicant' => 'Applicant',

            /*
             * The name of the ATC Secretariat role
             */
            'atc_secretariat' => 'ATC Secretariat',

            /*
             * The name of the Review Committee Team
             */
            'review' => 'Review Committee',

            /*
             * The name of the Voting Committee Team
             */
            'vote' => 'Council',
        ],

        'permission' => [

            'manage_user' => 'manage user',
            'restrict_view' => 'restrict view other application',
            'send_application' => 'send application',
            'update_application' => 'update application',
            'approve_application' => 'approve application',
            'disapprove_application' => 'disapprove application',
            'edit_narrative' => 'edit narrative',
            'comment_application' => 'comment application',
            'endorse_application' => 'endorse application',
            'vote_application' => 'vote application',
            'provide_resolution' => 'provide resolution',
            'view_discussion' => 'view discussion',
            'view_vote' => 'view vote',

            'non_restricted_user' => [
                'manage user',
                'send application',
                'update application',
                'approve application',
                'disapprove application',
                'edit narrative',
                'comment application',
                'endorse application',
                'vote application',
                'provide resolution',
            ],

            'applicant' => [
                'send application',
                'update application',
                'restrict view other application',
            ],

            'atc_secretariat' => [
                'approve application',
                'update application',
                'disapprove application',
                'edit narrative',
                'provide resolution',
                'view discussion',
                'view vote',
            ],

            'doj' => [
                'disapprove application',
                'comment application',
                'endorse application',
                'vote application',
                'view discussion',
                'view vote',
            ],

            'dilg' => [
                'comment application',
                'vote application',
            ],

            'dnd' => [
                'comment application',
                'vote application',
            ],

            'es' => [
                'vote application',
            ],

            'snd' => [
                'vote application',
            ],

            'nsa' => [
                'vote application',
            ],

            'sfa' => [
                'vote application',
            ],

            'sict' => [
                'vote application',
            ],

            'sof' => [
                'vote application',
            ],

            'ed' => [
                'vote application',
            ],

            'amlc' => [
                'vote application',
            ],
        ],
    ],
];
