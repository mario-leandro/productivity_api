<?php

require_once __DIR__ . '/../bootstrap/app.php';

$router = require __DIR__ . '/../routes/api.php';

Src\Core\Cors::handle();

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
