<?php
namespace SAE_PHP\controllers;

class Homepage
{
    public function execute(): void
    {
        (new \SAE_PHP\views\Homepage())->show();
    }
}