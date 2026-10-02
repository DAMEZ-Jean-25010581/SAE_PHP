<?php

namespace routes\base;

class Route
{
    private static array $routes = [];

    public static function add(string $path, $handler): void
    {
        self::$routes[$path] = $handler;
    }

    public static function dispatch(string $requestUri): void
    {
        $uri = parse_url($requestUri, PHP_URL_PATH) ?: '/';
        $uri = rtrim($uri, '/');
        if ($uri === '') {
            $uri = '/';
        }

        foreach (self::$routes as $path => $handler) {
            if ($path === $uri) {
                call_user_func($handler);
                return;
            }
        }

        http_response_code(404);
        echo "Page introuvable (404)";
    }
}