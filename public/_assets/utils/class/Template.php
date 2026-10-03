<?php

namespace utils;

class Template
{
    public static function render(string $view, array $params = []): void
    {
        $className = 'SAE_PHP\\views\\' . $view;
        if (class_exists($className)) {
            (new $className())->show();
            return;
        }

        $rootDir = dirname(__DIR__, 4);
        $viewPath = $rootDir . '/app/views/' . $view . '.php';

        if (!file_exists($viewPath)) {
            http_response_code(404);
            echo "Vue introuvable (404)";
            return;
        }

        extract($params);

        ob_start();
        include $viewPath;

        $content = ob_get_clean();

        (new \SAE_PHP\views\Layout($title ?? 'CyberLab', (string)$content))->show();
    }
}