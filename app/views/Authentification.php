<?php
namespace SAE_PHP\views;

class Authentification
{
    public function show(): void
    {
        ob_start();
        ?>
        <!-- Authentification -->
        <section>
            <h1 class="main-title">AUTHENTIFICATION</h1>
        </section>
        <?php
        (new \SAE_PHP\views\Layout('CyberLab - Authentification', (string)ob_get_clean()))->show();
    }
}
