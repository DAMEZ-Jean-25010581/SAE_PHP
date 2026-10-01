<?php
namespace SAE_PHP\views;

class PlanSite
{
    public function show(): void
    {
        ob_start();
        ?>
        <h1>Plan du site</h1>
        <ul>
            <li><a href="index.php">Accueil</a>
                <ul>
                    <li><a href="index.php#levels">Niveaux</a></li>
                    <li><a href="index.php#ranking">Classement</a></li>
                </ul>
            </li>
            <li><a href="index.php?action=a-propos">À propos</a></li>
            <li><a href="index.php?action=contact">Contact</a></li>
            <li><a href="index.php?action=login">Se connecter</a></li>
            <li><a href="index.php?action=register">S'inscrire</a></li> 
            <li><a href="index.php?action=mentions-legales">Mentions légales</a></li>
            <li><a href="index.php?action=plan-site">Plan du site</a></li>
        </ul>
        <?php
        (new \SAE_PHP\views\Layout('CyberLab - Plan du site', ob_get_clean()))->show();
    }
}