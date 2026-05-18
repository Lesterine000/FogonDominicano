<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Disco de sistema de archivos por defecto
    |--------------------------------------------------------------------------
    |
    | Aquí puedes especificar el disco de sistema de archivos por defecto que
    | usará el framework. El disco "local", así como varios discos basados en
    | la nube, están disponibles para el almacenamiento de archivos.
    |
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Discos del sistema de archivos
    |--------------------------------------------------------------------------
    |
    | A continuación puedes configurar tantos discos de sistema de archivos como
    | necesites, e incluso varios discos para el mismo driver. Se incluyen ejemplos
    | de la mayoría de drivers soportados como referencia.
    |
    | Drivers soportados: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app/private'),
            'serve' => true,
            'throw' => false,
            'report' => false,
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => rtrim(env('APP_URL', 'http://localhost'), '/').'/storage',
            'visibility' => 'public',
            'throw' => false,
            'report' => false,
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
            'report' => false,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Enlaces simbólicos
    |--------------------------------------------------------------------------
    |
    | Aquí puedes configurar los enlaces simbólicos que se crearán cuando se
    | ejecute el comando Artisan `storage:link`. Las claves del array deben ser
    | las ubicaciones de los enlaces y los valores deben ser sus destinos.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
