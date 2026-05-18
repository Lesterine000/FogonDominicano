<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Nombre de la conexión de colas por defecto
    |--------------------------------------------------------------------------
    |
    | La cola de Laravel soporta una variedad de backends mediante una API
    | única y unificada, dándote acceso cómodo a cada backend usando la misma
    | sintaxis. La conexión de colas por defecto se define a continuación.
    |
    */

    'default' => env('QUEUE_CONNECTION', 'database'),

    /*
    |--------------------------------------------------------------------------
    | Conexiones de colas
    |--------------------------------------------------------------------------
    |
    | Aquí puedes configurar las opciones de conexión para cada backend de colas
    | usado por tu aplicación. Se proporciona un ejemplo para cada backend
    | soportado por Laravel. También puedes añadir más.
    |
    | Drivers disponibles: "sync", "database", "beanstalkd", "sqs", "redis",
    |          "deferred", "background", "failover", "null"
    |
    */

    'connections' => [

        'sync' => [
            'driver' => 'sync',
        ],

        'database' => [
            'driver' => 'database',
            'connection' => env('DB_QUEUE_CONNECTION'),
            'table' => env('DB_QUEUE_TABLE', 'jobs'),
            'queue' => env('DB_QUEUE', 'default'),
            'retry_after' => (int) env('DB_QUEUE_RETRY_AFTER', 90),
            'after_commit' => false,
        ],

        'beanstalkd' => [
            'driver' => 'beanstalkd',
            'host' => env('BEANSTALKD_QUEUE_HOST', 'localhost'),
            'queue' => env('BEANSTALKD_QUEUE', 'default'),
            'retry_after' => (int) env('BEANSTALKD_QUEUE_RETRY_AFTER', 90),
            'block_for' => 0,
            'after_commit' => false,
        ],

        'sqs' => [
            'driver' => 'sqs',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'prefix' => env('SQS_PREFIX', 'https://sqs.us-east-1.amazonaws.com/your-account-id'),
            'queue' => env('SQS_QUEUE', 'default'),
            'suffix' => env('SQS_SUFFIX'),
            'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
            'after_commit' => false,
        ],

        'redis' => [
            'driver' => 'redis',
            'connection' => env('REDIS_QUEUE_CONNECTION', 'default'),
            'queue' => env('REDIS_QUEUE', 'default'),
            'retry_after' => (int) env('REDIS_QUEUE_RETRY_AFTER', 90),
            'block_for' => null,
            'after_commit' => false,
        ],

        'deferred' => [
            'driver' => 'deferred',
        ],

        'background' => [
            'driver' => 'background',
        ],

        'failover' => [
            'driver' => 'failover',
            'connections' => [
                'database',
                'deferred',
            ],
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Agrupación de jobs (batching)
    |--------------------------------------------------------------------------
    |
    | Las siguientes opciones configuran la base de datos y la tabla que
    | almacenan la información de batching de jobs. Puedes apuntarlas a cualquier
    | conexión y tabla definidas en tu aplicación.
    |
    */

    'batching' => [
        'database' => env('DB_CONNECTION', 'sqlite'),
        'table' => 'job_batches',
    ],

    /*
    |--------------------------------------------------------------------------
    | Jobs fallidos en cola
    |--------------------------------------------------------------------------
    |
    | Estas opciones configuran el registro de jobs fallidos en cola para que
    | puedas controlar cómo y dónde se almacenan. Laravel incluye soporte para
    | guardar jobs fallidos en un archivo simple o en una base de datos.
    |
    | Drivers soportados: "database-uuids", "dynamodb", "file", "null"
    |
    */

    'failed' => [
        'driver' => env('QUEUE_FAILED_DRIVER', 'database-uuids'),
        'database' => env('DB_CONNECTION', 'sqlite'),
        'table' => 'failed_jobs',
    ],

];
