<?php

// Autoload dependencies
require_once '../vendor/autoload.php';

// Load the routes
$router = require '../routes.php';

// Dispatch the current request
$router->dispatch();
