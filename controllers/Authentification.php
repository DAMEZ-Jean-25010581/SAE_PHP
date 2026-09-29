<?php
namespace SAE_PHP\controllers;

class Authentification
{
    public function execute(): void
    {
        (new \SAE_PHP\views\Authentification())->show();
    }
}