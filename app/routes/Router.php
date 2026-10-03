<?php

namespace routes;

use routes\base\Route;

class Router
{
    public function __construct()
    {
        new Web();

        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        Route::dispatch($uri);
    }
}