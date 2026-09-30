<?php
namespace SAE_PHP\views;
class Contact { 
    public function show(): void { 
        ob_start();
        ?>
        
        <!-- Ici HTML -->

        <?php
        (new \SAE_PHP\views\Layout('CyberLab - Contact', ob_get_clean()))->show();
    }
}