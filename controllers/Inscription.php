<?php
namespace SAE_PHP\controllers;

use Auth\Controllers\Register\Register;

class Inscription
{
    public function execute(): void
    {
        (new Register())->execute();
    }
}