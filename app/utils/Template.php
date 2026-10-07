<?php

namespace Utils;

class Template
{
    public static function render(string $view, array $params = []): void
    {
        $viewPath = __DIR__ . '/../views/' . $view . '.php';

        // permet de verifier si le fichier de vue existe
        if (!is_file($viewPath)) {
            http_response_code(404);
            echo 'Vue introuvable (404)';
            return;
        }

        // permet d'extraire les paramètres dans le contexte de la vue
        extract($params, EXTR_SKIP); // evite d'ecraser les variables existantes déjà définies

        //permet de récupérer le contenu du fichier de vue dans une variable $content
        ob_start();
        include $viewPath;
        $content = ob_get_clean();

        // On passe le contenu de la vue a la mise en page
        include __DIR__ . '/../views/layout.php';
    }
}