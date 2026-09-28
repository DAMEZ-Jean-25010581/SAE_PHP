<?php
namespace SAE_PHP\views;
class Homepage { 
    public function show(): void { 
        ob_start();
        ?><h1>Plan du site</h1>
        <ul>
        <li><a href="index.html">Accueil</a>
          <ul>
            <li><a href="index.html#levels">Niveaux</a></li>
            <li><a href="index.html#ranking">Classement</a></li>
          </ul>
        </li>
        <li><a href="a_propos.html">À propos</a></li>
        <li><a href="contact.html">Contact</a></li>
        <li><a href="authentification.php">Se connecter</a></li>
        <li><a href="inscription.php">S'inscrire</a></li> 
        <li><a href="mentions_legales.html">Mentions légales</a></li>
        <li><a href="plan_site.html">Plan du site</a></li>
        </ul><?php
        (new \SAE_PHP\views\Layout('CyberLab - Plan du site', ob_get_clean()))->show();
    }
}