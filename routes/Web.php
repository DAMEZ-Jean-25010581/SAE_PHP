<?php

namespace routes;

use routes\base\Route;

class Web
{
    public function __construct()
    {
        Route::add('/', function () {
            echo "<h1>CyberLab !</h1>";
        });
    }
}