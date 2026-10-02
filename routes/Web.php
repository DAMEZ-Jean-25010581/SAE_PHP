<?php

namespace routes;

use routes\base\Route;
use Utils\Template;
use Utils\SessionHelpers;

class Web
{
    public function __construct()
    {
        Route::add('/', function () {
            Template::render('Homepage', ['title' => 'CyberLab - Accueil']);
        });

        Route::add('/a-propos', function () {
            Template::render('APropos', ['title' => 'CyberLab - À propos']);
        });

        Route::add('/contact', function () {
            Template::render('Contact', ['title' => 'CyberLab - Contact']);
        });

        Route::add('/mentions-legales', function () {
            Template::render('MentionsLegales', ['title' => 'CyberLab - Mentions légales']);
        });

        


    }
}