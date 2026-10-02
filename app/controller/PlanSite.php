<?php
namespace SAE_PHP\controllers;

class PlanSite
{
    public function execute(): void
    {
        (new \SAE_PHP\views\PlanSite())->show();
    }
}