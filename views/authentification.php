<?php
namespace SAE_PHP\views;
class Homepage { 
    public function show(): void { 
        ob_start();
        ?>
        
        <!-- Ici HTML -->

        <?php
        (new \SAE_PHP\views\Layout('CyberLab - Authentification', ob_get_clean()))->show();
    }
}