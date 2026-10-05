<?php
namespace SAE_PHP\controllers;

class ContactController
{
    public function execute(): void
    {
        (new \SAE_PHP\app\views\Contact())->show();
    }
}