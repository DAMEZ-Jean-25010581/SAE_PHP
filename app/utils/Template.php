<?php

namespace Utils;

class Template
{
    public static function render(string $view, array $params = []): void
    {
        $viewPath = __DIR__ . '/../views/' . $view . '.php';

        if (!is_file($viewPath)) {
            http_response_code(404);
            echo 'Vue introuvable (404)';
            return;
        }

        extract($params, EXTR_SKIP);

        ob_start();
        include $viewPath;
        $content = ob_get_clean();

        include __DIR__ . '/../views/layout.php';
    }
}