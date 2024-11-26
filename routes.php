<?php

use SimpleEvents\Controllers\EventController;
use SimpleEvents\Core\Router;

$router = new Router();

// GET routes
$router->get('/', [EventController::class, 'index']);
$router->get('/events', [EventController::class, 'archive']);

// API routes
$router->get('/api/sports', [EventController::class, 'sports']);
$router->get('/api/teams', [EventController::class, 'teams']);
$router->get('/api/venues', [EventController::class, 'venues']);

// POST routes
$router->post('/events', [EventController::class, 'create']);

return $router;
