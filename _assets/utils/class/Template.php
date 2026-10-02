<?php

namespace utils;

class Template
{
    public static function render(string $view, array $params = []): void
    {
        $viewPath = __DIR__ . '/../../../views/' . $view . '.php';

        if (!file_exists($viewPath)) {
            http_response_code(404);
            echo "Vue introuvable (404)";
            return;
        }

        extract($params);

        ob_start();
        include $viewPath;

        $content = ob_get_clean();

        include __DIR__ . '/../../../views/layout.php';



    }
}