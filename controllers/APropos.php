<?php
namespace SAE_PHP\controllers;

class APropos
{
    public function execute(): void
    {
        (new \SAE_PHP\views\APropos())->show();
    }
}