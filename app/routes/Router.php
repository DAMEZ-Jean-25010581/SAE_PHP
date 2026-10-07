<?php

namespace routes;

use routes\base\Route;

class Router
{
    public function __construct()
    {
        // déclare les routes
        new Web();

        // recupere l'adresse demandée par le navigateur
        $uri = $_SERVER['REQUEST_URI'] ?? '/';

        // lance la route correspondante à l'adresse demandée (404 sinon)
        Route::dispatch($uri);
    }
}