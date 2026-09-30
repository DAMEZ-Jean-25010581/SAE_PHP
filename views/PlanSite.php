<?php
namespace SAE_PHP\views;
class PlanSite { 
    public function show(): void { 
        ob_start();
        ?><h1>Plan du site</h1>
        <ul>
        <li><a href="/">Accueil</a>
          <ul>
            <li><a href="/#levels">Niveaux</a></li>
            <li><a href="/#ranking">Classement</a></li>
          </ul>
        </li>
        <li><a href="/a-propos">À propos</a></li>
        <li><a href="/contact">Contact</a></li>
        <li><a href="/authentification">Se connecter</a></li>
        <li><a href="/inscription">S'inscrire</a></li> 
        <li><a href="/mentions-legales">Mentions légales</a></li>
        <li><a href="/plan-site">Plan du site</a></li>
        </ul><?php
        (new \SAE_PHP\views\Layout('CyberLab - Plan du site', ob_get_clean()))->show();
    }
}