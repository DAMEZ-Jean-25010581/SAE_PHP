<?php
namespace SAE_PHP\views;
class Inscription { 
    public function show(): void { 
        ob_start();
        ?>
        
        <!-- Ici HTML -->

        <?php
        (new \SAE_PHP\views\Layout('CyberLab - Inscription', ob_get_clean()))->show();
    }
}