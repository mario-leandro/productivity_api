<?php

require_once __DIR__ . '/../bootstrap/app.php';

$router = require __DIR__ . '/../routes/api.php';

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
