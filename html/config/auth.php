<?php

use App\Models\User;

return [

    /*
    |--------------------------------------------------------------------------
    | Valores por defecto de autenticación
    |--------------------------------------------------------------------------
    |
    | Esta opción define el "guard" de autenticación y el "broker" de reseteo
    | de contraseñas por defecto para tu aplicación. Puedes cambiar estos
    | valores según necesites, pero son un buen punto de partida.
    |
    */

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Guards de autenticación
    |--------------------------------------------------------------------------
    |
    | A continuación puedes definir cada guard de autenticación para tu
    | aplicación. Se proporciona una configuración por defecto que utiliza
    | almacenamiento en sesión junto con el proveedor de usuarios Eloquent.
    |
    | Todos los guards de autenticación tienen un proveedor de usuarios, que
    | define cómo se recuperan los usuarios de tu base de datos u otro sistema
    | de almacenamiento usado por la aplicación. Normalmente se usa Eloquent.
    |
    | Soportado: "session"
    |
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Proveedores de usuario
    |--------------------------------------------------------------------------
    |
    | Todos los guards de autenticación tienen un proveedor de usuarios, que
    | define cómo se recuperan los usuarios de tu base de datos u otro sistema
    | de almacenamiento usado por la aplicación. Normalmente se usa Eloquent.
    |
    | Si tienes múltiples tablas o modelos de usuarios, puedes configurar varios
    | proveedores para representar el modelo / tabla. Estos proveedores pueden
    | asignarse a guards adicionales que hayas definido.
    |
    | Soportado: "database", "eloquent"
    |
    */

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => env('AUTH_MODEL', User::class),
        ],

        // 'users' => [
        //     'driver' => 'database',
        //     'table' => 'users',
        // ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Restablecimiento de contraseñas
    |--------------------------------------------------------------------------
    |
    | Estas opciones de configuración especifican el comportamiento del
    | restablecimiento de contraseña de Laravel, incluyendo la tabla usada
    | para almacenar tokens y el proveedor de usuario que recupera los usuarios.
    |
    | El tiempo de expiración es el número de minutos durante el cual cada token
    | se considerará válido. Esto mantiene los tokens con una vida corta para
    | reducir el tiempo disponible para adivinarlos. Puedes ajustarlo si lo necesitas.
    |
    | El valor de throttle es el número de segundos que un usuario debe esperar
    | antes de generar más tokens de restablecimiento. Esto evita que se generen
    | rápidamente grandes cantidades de tokens.
    |
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Tiempo de espera de confirmación de contraseña
    |--------------------------------------------------------------------------
    |
    | Aquí puedes definir el número de segundos antes de que expire la ventana
    | de confirmación de contraseña y se pida al usuario que vuelva a introducirla.
    | Por defecto, el tiempo de espera es de tres horas.
    |
    */

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
