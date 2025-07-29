<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */
    'tbc' => [
        'merchant_id' => env('TBC_MERCHANT_ID'),
        'client_id' => env('TBC_CLIENT_ID'),
        'client_secret' => env('TBC_CLIENT_SECRET'),
        'api_key' => env('TBC_API_KEY'),  // Add API Key entry
        'api_secret' => env('TBC_API_SECRET'), // Add API Secret entry
        'api_url' => env('TBC_API_URL'),
        'redirect_url' => env('TBC_REDIRECT_URL'),
        'base_url' => env('TBC_BASE_URL'),
    ],
    
    

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

];
