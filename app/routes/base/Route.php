<?php

namespace routes\base;

class Route
{
    private static array $routes = [];

    public static function add(string $path, callable $handler): void
    {
        self::$routes[$path] = $handler;
    }

    public static function dispatch(string $requestUri = ''): void
    {
        $action = $_GET['action'] ?? null;
        if (!empty($action)) {
            $uri = '/' . ltrim($action, '/');
        } else {
            $rawPath = parse_url($requestUri ?: ($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH) ?: '/';
            $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
            $scriptDir = dirname($scriptName);

            if ($scriptName && str_starts_with($rawPath, $scriptName)) {
                $rawPath = substr($rawPath, strlen($scriptName));
            } elseif ($scriptDir && $scriptDir !== '/' && str_starts_with($rawPath, $scriptDir)) {
                $rawPath = substr($rawPath, strlen($scriptDir));
            }

            $uri = rtrim($rawPath, '/');
            if ($uri === '' || $uri === '/index.php') {
                $uri = '/';
            }
        }

        foreach (self::$routes as $path => $handler) {
            if ($path === $uri) {
                call_user_func($handler);
                return;
            }
        }

        if (isset(self::$routes['/'])) {
            call_user_func(self::$routes['/']);
            return;
        }

        http_response_code(404);
        echo "Page introuvable (404)";
    }
}