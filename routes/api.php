<?php

use Src\Core\Router;
use Src\Controllers\AuthController;
use Src\Controllers\TaskController;

$router = new Router();

$auth = new AuthController();
$task = new TaskController();

// Auth Route
$router->post('/api/auth/register', [$auth, 'register']);
$router->post('/api/auth/login', [$auth, 'login']);

// ME Route
$router->get('/api/auth/me', [$auth, 'me']);

// Task Routes
$router->get('/api/tasks', [$task, 'index']);
$router->post('/api/tasks', [$task, 'store']);
$router->patch('/api/tasks/{id}/status', [$task, 'updateStatus']);

return $router;
