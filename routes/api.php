<?php

use Src\Core\Router;
use Src\Controllers\AuthController;

$router = new Router();

$auth = new AuthController();

$router->post('/api/auth/register', [$auth, 'register']);
$router->post('/api/auth/login', [$auth, 'login']);
$router->get('/api/auth/me', [$auth, 'me']);

return $router;
