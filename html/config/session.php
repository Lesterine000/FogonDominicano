<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Driver de sesión por defecto
    |--------------------------------------------------------------------------
    |
    | Esta opción determina el driver de sesión por defecto que se utiliza en
    | las solicitudes entrantes. Laravel soporta varias opciones de almacenamiento
    | para persistir los datos de sesión. Guardarlo en base de datos es una buena opción.
    |
    | Soportados: "file", "cookie", "database", "memcached",
    |             "redis", "dynamodb", "array"
    |
    */

    'driver' => env('SESSION_DRIVER', 'database'),

    /*
    |--------------------------------------------------------------------------
    | Duración de la sesión
    |--------------------------------------------------------------------------
    |
    | Aquí puedes especificar el número de minutos que permites que la sesión
    | permanezca inactiva antes de expirar. Si quieres que expire inmediatamente
    | al cerrar el navegador, puedes indicarlo con la opción expire_on_close.
    |
    */

    'lifetime' => (int) env('SESSION_LIFETIME', 120),

    'expire_on_close' => env('SESSION_EXPIRE_ON_CLOSE', false),

    /*
    |--------------------------------------------------------------------------
    | Cifrado de sesión
    |--------------------------------------------------------------------------
    |
    | Esta opción te permite indicar fácilmente que todos los datos de sesión
    | deben cifrarse antes de almacenarse. Laravel realiza el cifrado de forma
    | automática y puedes usar la sesión con normalidad.
    |
    */

    'encrypt' => env('SESSION_ENCRYPT', false),

    /*
    |--------------------------------------------------------------------------
    | Ubicación de archivos de sesión
    |--------------------------------------------------------------------------
    |
    | Al utilizar el driver de sesión "file", los archivos de sesión se guardan
    | en disco. Aquí se define la ubicación por defecto; no obstante, puedes
    | indicar otra ubicación donde deban almacenarse.
    |
    */

    'files' => storage_path('framework/sessions'),

    /*
    |--------------------------------------------------------------------------
    | Conexión de base de datos de sesión
    |--------------------------------------------------------------------------
    |
    | Al usar los drivers de sesión "database" o "redis", puedes especificar
    | la conexión que se usará para gestionar estas sesiones. Debe corresponder
    | con una conexión en tu configuración de base de datos.
    |
    */

    'connection' => env('SESSION_CONNECTION'),

    /*
    |--------------------------------------------------------------------------
    | Tabla de sesión en base de datos
    |--------------------------------------------------------------------------
    |
    | Al usar el driver de sesión "database", puedes especificar la tabla que
    | se utilizará para almacenar sesiones. Se define un valor razonable por
    | defecto, pero puedes cambiarlo por otra tabla si lo deseas.
    |
    */

    'table' => env('SESSION_TABLE', 'sessions'),

    /*
    |--------------------------------------------------------------------------
    | Store de caché para la sesión
    |--------------------------------------------------------------------------
    |
    | Al usar uno de los backends de sesión basados en caché del framework, puedes
    | definir el store de caché que se usará para almacenar los datos de sesión entre
    | solicitudes. Debe coincidir con uno de los stores de caché definidos.
    |
    | Afecta a: "dynamodb", "memcached", "redis"
    |
    */

    'store' => env('SESSION_STORE'),

    /*
    |--------------------------------------------------------------------------
    | Lotería de limpieza (sweeping) de sesiones
    |--------------------------------------------------------------------------
    |
    | Algunos drivers de sesión deben limpiar manualmente su almacenamiento para
    | eliminar sesiones antiguas. Aquí se definen las probabilidades de que ocurra
    | en una solicitud dada. Por defecto, la probabilidad es 2 de cada 100.
    |
    */

    'lottery' => [2, 100],

    /*
    |--------------------------------------------------------------------------
    | Nombre de la cookie de sesión
    |--------------------------------------------------------------------------
    |
    | Aquí puedes cambiar el nombre de la cookie de sesión que crea el framework.
    | Normalmente no deberías necesitar cambiar este valor, ya que hacerlo no aporta
    | una mejora de seguridad significativa.
    |
    */

    'cookie' => env(
        'SESSION_COOKIE',
        Str::slug((string) env('APP_NAME', 'laravel')).'-session'
    ),

    /*
    |--------------------------------------------------------------------------
    | Ruta de la cookie de sesión
    |--------------------------------------------------------------------------
    |
    | La ruta de la cookie de sesión determina para qué ruta se considerará
    | disponible la cookie. Normalmente será la ruta raíz de la aplicación,
    | pero puedes cambiarlo cuando sea necesario.
    |
    */

    'path' => env('SESSION_PATH', '/'),

    /*
    |--------------------------------------------------------------------------
    | Dominio de la cookie de sesión
    |--------------------------------------------------------------------------
    |
    | Este valor determina el dominio y subdominios en los que la cookie de sesión
    | estará disponible. Por defecto, la cookie estará disponible para el dominio raíz
    | sin subdominios. Normalmente, no debería cambiarse.
    |
    */

    'domain' => env('SESSION_DOMAIN'),

    /*
    |--------------------------------------------------------------------------
    | Cookies solo HTTPS
    |--------------------------------------------------------------------------
    |
    | Al establecer esta opción en true, las cookies de sesión solo se enviarán
    | al servidor si el navegador tiene una conexión HTTPS. Esto evita que la cookie
    | se envíe cuando no se pueda hacer de forma segura.
    |
    */

    'secure' => env('SESSION_SECURE_COOKIE'),

    /*
    |--------------------------------------------------------------------------
    | Acceso solo HTTP (HttpOnly)
    |--------------------------------------------------------------------------
    |
    | Establecer este valor en true impedirá que JavaScript acceda al valor de
    | la cookie, y esta solo será accesible a través del protocolo HTTP. Es poco
    | probable que debas deshabilitar esta opción.
    |
    */

    'http_only' => env('SESSION_HTTP_ONLY', true),

    /*
    |--------------------------------------------------------------------------
    | Cookies Same-Site
    |--------------------------------------------------------------------------
    |
    | Esta opción determina cómo se comportan tus cookies cuando se producen
    | solicitudes cross-site, y puede usarse para mitigar ataques CSRF. Por defecto,
    | se establece este valor en "lax" para permitir solicitudes cross-site seguras.
    |
    | Ver: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Set-Cookie#samesitesamesite-value
    |
    | Soportados: "lax", "strict", "none", null
    |
    */

    'same_site' => env('SESSION_SAME_SITE', 'lax'),

    /*
    |--------------------------------------------------------------------------
    | Cookies particionadas
    |--------------------------------------------------------------------------
    |
    | Establecer este valor en true vinculará la cookie al sitio de nivel superior
    | en un contexto cross-site. Las cookies particionadas son aceptadas por el navegador
    | cuando están marcadas como "secure" y el atributo Same-Site se establece en "none".
    |
    */

    'partitioned' => env('SESSION_PARTITIONED_COOKIE', false),

    /*
    |--------------------------------------------------------------------------
    | Serialización de la sesión
    |--------------------------------------------------------------------------
    |
    | Este valor controla la estrategia de serialización de los datos de sesión,
    | que por defecto es JSON. Establecerlo en "php" permite almacenar objetos PHP
    | en la sesión, pero puede hacer la aplicación vulnerable a ataques de serialización
    | tipo "gadget chain" si se filtra tu APP_KEY.
    |
    | Soportados: "json", "php"
    |
    */

    'serialization' => 'json',

];
