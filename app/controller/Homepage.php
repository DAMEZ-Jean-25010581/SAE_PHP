<?php
namespace SAE_PHP\controllers;

class Homepage
{
    public function execute(): void
    {
        \Utils\Template::render('homepage');
    }
}