<?php
namespace SAE_PHP\controllers;

class Contact
{
    public function execute(): void
    {
        (new \SAE_PHP\views\Contact())->show();
    }
}