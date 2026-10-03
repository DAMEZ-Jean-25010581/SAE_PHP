<?php
namespace SAE_PHP\views;

class Error
{
    public function show(): void
    {
        ob_start();
        ?>
        <section>
            <h1 class="main-title">404</h1>
            <h2 class="subtitle">Page non trouvée</h2>
            <p class="description">La page que vous recherchez n'existe pas ou a été déplacée.</p>
            <a href="/" class="btn">Retour à l'accueil</a>
        </section>
        <?php
        (new \SAE_PHP\views\Layout('CyberLab - 404', (string)ob_get_clean()))->show();
    }
}
