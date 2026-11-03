<?php

namespace routes;

use routes\base\Route;

class Router
{
    public function __construct()
    {
        // déclare les routes
        new Web();

        // lance la route correspondante à l'action demandée (404 sinon)
        Route::dispatch();
    }
}