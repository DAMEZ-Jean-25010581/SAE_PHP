<?php
namespace SAE_PHP\controllers;

class MentionsLegales
{
    public function execute(): void
    {
        (new \SAE_PHP\views\MentionsLegales())->show();
    }
}