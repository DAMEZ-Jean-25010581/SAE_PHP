<?php

namespace SAE_PHP\controllers;

use Utils\Template;
use SAE_PHP\views\ModulesView;

class ModulesController
{
    private const LEVELS = [
        1 => 'Débutant',
        2 => 'Intermédiaire',
        3 => 'Avancé',
    ];

    private const MODULES = [
        ['tag' => 'PHISH', 'title' => 'Module 1', 'level' => 1,
         'description' => 'Wanis le best '],
        ['tag' => 'SQL', 'title' => 'Module 2', 'level' => 1,
         'description' => 'Wanis le best.'],
        ['tag' => 'IDOR', 'title' => 'Module 3', 'level' => 1,
         'description' => "Wanis le best."],
        ['tag' => 'XSS', 'title' => 'Module 4', 'level' => 2,
         'description' => 'Wanis le best.'],
        ['tag' => 'CSRF', 'title' => 'Module 5', 'level' => 2,
         'description' => "Wanis le best."],
        ['tag' => 'HASH', 'title' => 'Module 6', 'level' => 2,
         'description' => 'Wanis le best.'],
        ['tag' => 'CMD', 'title' => 'Module 7', 'level' => 3,
         'description' => "Wanis le best"],
        ['tag' => 'FILE', 'title' => 'Module 8', 'level' => 3,
         'description' => 'Wanis le best'],
    ];

    public function execute(): void
    {
        Template::render(ModulesView::class, [
            'title'   => 'CyberLab - Modules',
            'modules' => self::MODULES,
            'levels'  => self::LEVELS,
        ]);
    }
}
