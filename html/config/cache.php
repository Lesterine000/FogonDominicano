<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Almacén de caché por defecto
    |--------------------------------------------------------------------------
    |
    | Esta opción controla el almacén de caché por defecto que usará el
    | framework. Se utilizará esta conexión si no se especifica otra de forma
    | explícita al ejecutar una operación de caché dentro de la aplicación.
    |
    */

    'default' => env('CACHE_STORE', 'database'),

    /*
    |--------------------------------------------------------------------------
    | Almacenes de caché
    |--------------------------------------------------------------------------
    |
    | Aquí puedes definir todos los "stores" de caché para tu aplicación, así
    | como sus drivers. Incluso puedes definir múltiples stores para el mismo
    | driver de caché para agrupar tipos de elementos guardados en caché.
    |
    | Drivers soportados: "array", "database", "file", "memcached",
    |                     "redis", "dynamodb", "octane",
    |                     "failover", "null"
    |
    */

    'stores' => [

        'array' => [
            'driver' => 'array',
            'serialize' => false,
        ],

        'database' => [
            'driver' => 'database',
            'connection' => env('DB_CACHE_CONNECTION'),
            'table' => env('DB_CACHE_TABLE', 'cache'),
            'lock_connection' => env('DB_CACHE_LOCK_CONNECTION'),
            'lock_table' => env('DB_CACHE_LOCK_TABLE'),
        ],

        'file' => [
            'driver' => 'file',
            'path' => storage_path('framework/cache/data'),
            'lock_path' => storage_path('framework/cache/data'),
        ],

        'memcached' => [
            'driver' => 'memcached',
            'persistent_id' => env('MEMCACHED_PERSISTENT_ID'),
            'sasl' => [
                env('MEMCACHED_USERNAME'),
                env('MEMCACHED_PASSWORD'),
            ],
            'options' => [
                // Ejemplo: Memcached::OPT_CONNECT_TIMEOUT => 2000,
            ],
            'servers' => [
                [
                    'host' => env('MEMCACHED_HOST', '127.0.0.1'),
                    'port' => env('MEMCACHED_PORT', 11211),
                    'weight' => 100,
                ],
            ],
        ],

        'redis' => [
            'driver' => 'redis',
            'connection' => env('REDIS_CACHE_CONNECTION', 'cache'),
            'lock_connection' => env('REDIS_CACHE_LOCK_CONNECTION', 'default'),
        ],

        'dynamodb' => [
            'driver' => 'dynamodb',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
            'table' => env('DYNAMODB_CACHE_TABLE', 'cache'),
            'endpoint' => env('DYNAMODB_ENDPOINT'),
        ],

        'octane' => [
            'driver' => 'octane',
        ],

        'failover' => [
            'driver' => 'failover',
            'stores' => [
                'database',
                'array',
            ],
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Prefijo de claves de caché
    |--------------------------------------------------------------------------
    |
    | Al usar stores de caché como APC, database, memcached, Redis o DynamoDB,
    | puede haber otras aplicaciones usando la misma caché. Por ese motivo,
    | puedes prefijar todas las claves para evitar colisiones.
    |
    */

    'prefix' => env('CACHE_PREFIX', Str::slug((string) env('APP_NAME', 'laravel')).'-cache-'),

    /*
    |--------------------------------------------------------------------------
    | Clases deserializables
    |--------------------------------------------------------------------------
    |
    | Este valor determina qué clases pueden deserializarse desde el almacenamiento
    | de caché. Por defecto, no se deserializa ninguna clase PHP desde la caché
    | para evitar ataques de "gadget chain" si se filtra tu APP_KEY.
    |
    */

    'serializable_classes' => false,

];
