<?php
namespace SAE_PHP\views;

class APropos
{
    public function show(): void
    {
        ob_start();
        ?>
        <!-- À propos -->
        <section>
            <h1 class="main-title">À PROPOS</h1>
            <p class="description">Plateforme pédagogique d'apprentissage de la cybersécurité.</p>
        </section>
        <?php
        (new \SAE_PHP\views\Layout('CyberLab - À propos', (string)ob_get_clean()))->show();
    }
}
