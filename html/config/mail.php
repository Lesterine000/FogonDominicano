<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Mailer por defecto
    |--------------------------------------------------------------------------
    |
    | Esta opción controla el mailer por defecto que se utiliza para enviar
    | todos los correos, salvo que se especifique explícitamente otro mailer
    | al enviar el mensaje. Los mailers adicionales pueden configurarse en el
    | array "mailers". Se incluyen ejemplos de cada tipo de mailer.
    |
    */

    'default' => env('MAIL_MAILER', 'log'),

    /*
    |--------------------------------------------------------------------------
    | Configuraciones de mailer
    |--------------------------------------------------------------------------
    |
    | Aquí puedes configurar todos los mailers usados por tu aplicación, junto
    | con sus ajustes. Se incluyen varios ejemplos y puedes añadir los tuyos
    | según lo requiera la aplicación.
    |
    | Laravel soporta una variedad de drivers de "transporte" para el correo
    | que pueden usarse al entregar emails. Puedes especificar cuál estás usando
    | para tus mailers a continuación. También puedes añadir mailers adicionales.
    |
    | Soportados: "smtp", "sendmail", "mailgun", "ses", "ses-v2",
    |             "postmark", "resend", "log", "array",
    |             "failover", "roundrobin"
    |
    */

    'mailers' => [

        'smtp' => [
            'transport' => 'smtp',
            'scheme' => env('MAIL_SCHEME'),
            'url' => env('MAIL_URL'),
            'host' => env('MAIL_HOST', '127.0.0.1'),
            'port' => env('MAIL_PORT', 2525),
            'username' => env('MAIL_USERNAME'),
            'password' => env('MAIL_PASSWORD'),
            'timeout' => null,
            'local_domain' => env('MAIL_EHLO_DOMAIN', parse_url((string) env('APP_URL', 'http://localhost'), PHP_URL_HOST)),
        ],

        'ses' => [
            'transport' => 'ses',
        ],

        'postmark' => [
            'transport' => 'postmark',
        ],

        'resend' => [
            'transport' => 'resend',
        ],

        'sendmail' => [
            'transport' => 'sendmail',
            'path' => env('MAIL_SENDMAIL_PATH', '/usr/sbin/sendmail -bs -i'),
        ],

        'log' => [
            'transport' => 'log',
            'channel' => env('MAIL_LOG_CHANNEL'),
        ],

        'array' => [
            'transport' => 'array',
        ],

        'failover' => [
            'transport' => 'failover',
            'mailers' => [
                'smtp',
                'log',
            ],
            'retry_after' => 60,
        ],

        'roundrobin' => [
            'transport' => 'roundrobin',
            'mailers' => [
                'ses',
                'postmark',
            ],
            'retry_after' => 60,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Dirección global del remitente
    |--------------------------------------------------------------------------
    |
    | Puede que quieras que todos los correos enviados por tu aplicación salgan
    | desde la misma dirección. Aquí puedes especificar un nombre y una dirección
    | que se usará de forma global para todos los correos que envíe la aplicación.
    |
    */

    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
        'name' => env('MAIL_FROM_NAME', env('APP_NAME', 'Laravel')),
    ],

];
