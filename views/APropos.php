<?php
namespace SAE_PHP\views;
class APropos { 
    public function show(): void { 
        ob_start();
        ?>
        
        <!-- Ici HTML -->

        <?php
        (new \SAE_PHP\views\Layout('CyberLab - A propos', ob_get_clean()))->show();
    }
}