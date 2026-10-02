<?php
namespace SAE_PHP\views;
class APropos { 
    public function show(): void { 
        ob_start();
        ?>
        <!DOCTYPE html>
        <html lang="fr">
            <head>
                <meta charset="UTF-8">
                <title>A propos de nous</title>
            </head>
            <body>
                <h1 class="main-title">CYBERLAB</h1>
                <p class="description">
                    <span>CyberLab est né d’un projet d’étudiants en informatique.</span><br> 
                    <span>Sa visée principale est pédagogique : montrer par le biais d’activités amusantes les risques qui pèsent sur<span><br>
                    <span>toutes les applications : les attaques.<span><br>
                    <span>La démonstration et l’application des méthodes sont plus parlantes que des explications théoriques.<span><br>
                    <span>Nous espérons avoir introduit de nouvelles personnes dans le domaine de la cybersécurité ou avoir approfondi<span><br>
                    <span>vos connaissances.<span><br><br>
                    <span>Les commandes, attaques et manipulations malveillantes présentées dans ce site sont uniquement à
                    <span class="blue-word"> visée pédagogique</span>. Veuillez ne pas les reproduire sur des services publics. Nous ne sommes pas responsables des actions de nos utilisateurs qui viendraient déroger à nos consignes.</span>
                </p>
            </body>
        </html>

        <?php
        (new \SAE_PHP\views\Layout('CyberLab - A propos', ob_get_clean()))->show();
    }
}