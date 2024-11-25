<?php

use SimpleEvents\Controllers\EventController;
use SimpleEvents\Core\Router;

$router = new Router();

// GET routes
$router->get('/', [EventController::class, 'index']);
$router->get('/events', [EventController::class, 'archive']);

// POST routes
$router->post('/events', [EventController::class, 'create']);

return $router;
