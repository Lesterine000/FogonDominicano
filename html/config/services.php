<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Servicios de terceros
    |--------------------------------------------------------------------------
    |
    | Este archivo sirve para almacenar las credenciales de servicios de
    | terceros como Mailgun, Postmark, AWS y otros. Proporciona el lugar
    | de facto para este tipo de información, permitiendo que los paquetes
    | encuentren las credenciales de servicio en un archivo convencional.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI'),
],
];
