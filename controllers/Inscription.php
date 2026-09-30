<?php
namespace SAE_PHP\controllers;

class Inscription
{
    public function execute(): void
    {
        (new \SAE_PHP\views\Inscription())->show();
    }
}