<?php

namespace Src\Core;

use Src\Helpers\Helper;

class Router
{
    private array $routes = [];

    public function get(string $path, callable $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, callable $handler): void
    {
        $this->routes['POST'][$path] = $handler;
    }

    public function dispatch(string $method, string $uri): void
    {
        $path = parse_url($uri, PHP_URL_PATH);

        $routes = $this->routes[$method] ?? [];

        foreach ($routes as $route => $handler) {

            $pattern = preg_replace('#\{[^/]+\}#', '([^/]+)', $route);
            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $path, $matches)) {

                array_shift($matches);

                $handler(...$matches);

                return;
            }
        }

        if (!$handler) {
            Helper::Response([
                'success' => false,
                'message' => 'Rota não encontrada'
            ], 404);
        }
    }

    public function patch(string $path, callable $handler): void
    {
        $this->routes['PATCH'][$path] = $handler;
    }

    public function put(string $path, callable $handler): void
    {
        $this->routes['PUT'][$path] = $handler;
    }

    public function delete(string $path, callable $handler): void
    {
        $this->routes['DELETE'][$path] = $handler;
    }
}
