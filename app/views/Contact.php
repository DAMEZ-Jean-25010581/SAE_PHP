<?php
namespace SAE_PHP\views;

class Contact
{
    public function show(): void
    {
        ob_start();
        ?>
        <!-- Contact -->
        <section>
            <h1 class="main-title">CONTACT</h1>
            <p class="description">Pour nous contacter : contact@cyberlab.local</p>
        </section>
        <?php
        (new \SAE_PHP\views\Layout('CyberLab - Contact', (string)ob_get_clean()))->show();
    }
}
