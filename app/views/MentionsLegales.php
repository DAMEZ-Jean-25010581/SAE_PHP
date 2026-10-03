<?php
namespace SAE_PHP\views;
class MentionsLegales { 
    public function show(): void { 
        ob_start();
        ?><h2>Éditeur du site</h2>

                <p>Ce site a été conçu par une équipe d'étudiants dans le cadre d'un projet de formation en BUT Informatique, au titre de l'année scolaire 2026-2076.
                    Il n'a aucune vocation commerciale et est présenté à des fins purement pédagogiques.<br>
                    Contact : lucile.boix@univ-amu.fr </p>

            <h2>Directeur de publication</h2>

                <p>Directrice de publication : Mme Christine MAKSSOUD</p>

            <h2>Hébergeur</h2>

                <p>Hébergé par AlwaysData, société immatriculée au RCS de Paris sous le numéro 492 893 490 dont le siège social se trouve 91 rue du Faubourg Saint Honoré - 75008 Paris.
                <br>Contact : +33 1 84 16 23 40</p>

            <h2>Propriété intellectuelle</h2>

                <p>L'ensemble des contenus présents sur ce site (textes, images, graphismes, logo, code source) sont, sauf mention contraire, la propriété exclusive de leurs auteurs et sont protégés par le Code de la propriété intellectuelle. Toute reproduction, distribution ou utilisation sans autorisation préalable est interdite.</p>

            <h2>Données personnelles</h2>

                <h3>Données collectées</h3>
                    <p>Les données collectées par CyberLab concernent : e-mail, mot de passe, pseudonyme, adresse IP et cookies de session. Ces données sont utilisées à des fins strictement fonctionnelles.</p>

                <h3>Durée de conservation</h3>
                    <p>Vos données sont conservées tant que votre compte est actif, jusqu'à la suppression de
                    votre compte par vos soins. Vous pouvez à tout moment exercer vos droits d'accès, de rectification et de suppression sur vos données en accédant aux paramètres de votre compte.</p>

            <h2>Cookies</h2>
                    <p>Ce site utilise un cookie technique de session (PHPSESSID), nécessaire au bon fonctionnement de la connexion utilisateur. Ce cookie ne collecte aucune donnée à des fins publicitaires ou de tracking, et est automatiquement supprimé à la fermeture du navigateur ou à la déconnexion.</p>

            <h2>Limite de responsabilité</h2>

                <p>CyberLab s'efforce d'assurer l'exactitude des informations diffusées sur ce site, mais ne peut garantir l'absence d'erreurs ou d'interruptions de service. L'utilisation du site se fait sous la responsabilité de l'utilisateur.
            Les commandes, attaques et manipulations malveillantes présentées dans ce site sont uniquement à visée pédagogique. Veuillez ne pas les reproduire sur des services publics. Nous ne sommes pas responsables des actions des utilisateurs qui viendraient déroger à nos consignes.</p> 
            
            <h2>Droit applicable</h2>

                <p>Le présent site est soumis au droit français. En cas de litige, les tribunaux français seront seuls compétents.</p><?php

        (new \SAE_PHP\views\Layout('CyberLab - Mentions légales', ob_get_clean()))->show();
    }
}