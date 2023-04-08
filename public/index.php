<?php

require_once __DIR__ . '/../includes/app.php';

use MVC\Router;
use Controllers\PropiedadController;

$router = new Router();


$router->get('/admin', [PropiedadController::class, 'index']);  // **[PropiedadController::class]** Identifica en que clase se encuentra el arreglo
$router->get('/propiedades/crear', [PropiedadController::class, 'crear']);
$router->get('/propiedades/actualizar', [PropiedadController::class, 'actualizar']);


$router->comprobarRutas();
