<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Nombre de la aplicación
    |--------------------------------------------------------------------------
    |
    | Este valor es el nombre de tu aplicación, que se utilizará cuando el
    | framework necesite mostrar el nombre de la aplicación en una notificación
    | u otros elementos de la interfaz donde deba mostrarse.
    |
    */

    'name' => env('APP_NAME', 'Laravel'),

    /*
    |--------------------------------------------------------------------------
    | Entorno de la aplicación
    |--------------------------------------------------------------------------
    |
    | Este valor determina el "entorno" en el que se está ejecutando la
    | aplicación. Esto puede influir en cómo prefieras configurar los distintos
    | servicios que utiliza la aplicación. Configúralo en tu archivo ".env".
    |
    */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Modo debug de la aplicación
    |--------------------------------------------------------------------------
    |
    | Cuando la aplicación está en modo debug, se mostrarán mensajes de error
    | detallados con trazas en cada error que ocurra. Si está deshabilitado, se
    | mostrará una página de error genérica y simple.
    |
    */

    'debug' => (bool) env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | URL de la aplicación
    |--------------------------------------------------------------------------
    |
    | Esta URL la utiliza la consola para generar correctamente URLs cuando se
    | usa la herramienta de línea de comandos Artisan. Deberías configurarla
    | con la raíz de la aplicación para que esté disponible en los comandos.
    |
    */

    'url' => env('APP_URL', 'http://localhost'),

    /*
    |--------------------------------------------------------------------------
    | Zona horaria de la aplicación
    |--------------------------------------------------------------------------
    |
    | Aquí puedes especificar la zona horaria por defecto de la aplicación, que
    | será utilizada por las funciones de fecha y fecha/hora de PHP. Por defecto
    | se establece en "UTC", ya que es adecuada para la mayoría de casos de uso.
    |
    */

    // La lógica del menú del día depende de la fecha local; mantenlo configurable.
    'timezone' => env('APP_TIMEZONE', 'Europe/Madrid'),

    /*
    |--------------------------------------------------------------------------
    | Configuración regional (locale) de la aplicación
    |--------------------------------------------------------------------------
    |
    | El locale de la aplicación determina el locale por defecto que usarán los
    | métodos de traducción / localización de Laravel. Puedes establecerlo a
    | cualquier locale para el que planees tener cadenas de traducción.
    |
    */

    'locale' => env('APP_LOCALE', 'en'),

    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),

    'faker_locale' => env('APP_FAKER_LOCALE', 'en_US'),

    /*
    |--------------------------------------------------------------------------
    | Clave de cifrado
    |--------------------------------------------------------------------------
    |
    | Esta clave la utilizan los servicios de cifrado de Laravel y debe
    | establecerse a una cadena aleatoria de 32 caracteres para garantizar que
    | todos los valores cifrados sean seguros. Hazlo antes de desplegar.
    |
    */

    'cipher' => 'AES-256-CBC',

    'key' => env('APP_KEY'),

    'previous_keys' => [
        ...array_filter(
            explode(',', (string) env('APP_PREVIOUS_KEYS', ''))
        ),
    ],

    /*
    |--------------------------------------------------------------------------
    | Driver de modo mantenimiento
    |--------------------------------------------------------------------------
    |
    | Estas opciones de configuración determinan el driver que se usa para
    | determinar y gestionar el estado del "modo mantenimiento" de Laravel. El
    | driver "cache" permite controlar el modo mantenimiento en varias máquinas.
    |
    | Drivers soportados: "file", "cache"
    |
    */

    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store' => env('APP_MAINTENANCE_STORE', 'database'),
    ],

];
