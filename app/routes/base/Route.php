<?php

namespace routes\base;

use Utils\Template;

class Route
{
    private static array $routes = [];

    public static function add(string $path, callable $handler): void
    {
        self::$routes[$path] = $handler; // ajoute une route à la liste des routes
    }

    public static function dispatch(string $requestUri = ''): void
    {
        // Récupère l'action de l'URL (login, register, etc.)
        $action = $_GET['action'] ?? null;
        if (!empty($action)) {
            $uri = '/' . ltrim($action, '/');
        } else {
            // Récupère le chemin sans les paramètres après le "?"
            $rawPath = parse_url($requestUri ?: ($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH) ?: '/';
            $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
            $scriptDir = dirname($scriptName);

            // obtient un chemin relatif à l'application
            if ($scriptName && str_starts_with($rawPath, $scriptName)) {
                $rawPath = substr($rawPath, strlen($scriptName));
            } elseif ($scriptDir && $scriptDir !== '/' && str_starts_with($rawPath, $scriptDir)) {
                $rawPath = substr($rawPath, strlen($scriptDir));
            }

            // pas de '/' final et racine toujours '/'
            $uri = rtrim($rawPath, '/');
            if ($uri === '' || $uri === '/index.php') {
                $uri = '/';
            }
        }

        // cherche une route declarée avec chemin identique à l'uri demandée ex : '/login' et '/account'
        foreach (self::$routes as $path => $handler) {
            if ($path === $uri) {
                call_user_func($handler);
                return;
            }
        }

        // Si aucune route -> on affiche une page d'erreur 404
        http_response_code(404);
        Template::render('error', [
            'title' => 'CyberLab - Page non trouvée'
        ]);
    }
}