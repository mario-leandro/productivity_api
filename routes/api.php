<?php

use Src\Core\Router;
use Src\Controllers\AuthController;
use Src\Controllers\TaskController;
use Src\Controllers\NoteController;

$router = new Router();

$auth = new AuthController();
$task = new TaskController();
$note = new NoteController();

// Auth Route
$router->post('/api/auth/register', [$auth, 'register']);
$router->post('/api/auth/login', [$auth, 'login']);

// ME Route
$router->get('/api/auth/me', [$auth, 'me']);

// Task Routes
$router->get('/api/tasks', [$task, 'index']);
$router->post('/api/tasks', [$task, 'store']);
$router->patch('/api/tasks/{id}/status', [$task, 'updateStatus']);
$router->put('/api/tasks/{id}', [$task, 'update']);
$router->delete('/api/tasks/{id}', [$task, 'destroy']);

// Note Routes
$router->get('/api/notes', [$note, 'index']);
$router->post('/api/notes', [$note, 'create']);
$router->put('/api/notes/{id}', [$note, 'update']);


return $router;
