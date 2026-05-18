<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determinar si la aplicación está en modo mantenimiento...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Registrar el autocargador de Composer...
require __DIR__.'/../vendor/autoload.php';

// Inicializar Laravel y gestionar la solicitud...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
