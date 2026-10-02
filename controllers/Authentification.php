<?php
namespace SAE_PHP\controllers;

use Auth\Controllers\Login\Login;

class Authentification
{
    public function execute(): void
    {
        (new Login())->execute();
    }
}