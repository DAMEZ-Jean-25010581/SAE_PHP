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
    }
}