<?php

namespace routes;

use routes\base\Route;

class Web
{
    public function __construct()
    {
        Route::add('/', function () {
            (new \SAE_PHP\controllers\Homepage())->execute();
        });

        Route::add('/home', function () {
            (new \SAE_PHP\controllers\Homepage())->execute();
        });

        Route::add('/login', function () {
            (new \Auth\Controllers\Login\Login())->execute();
        });

        Route::add('/authentification', function () {
            (new \Auth\Controllers\Login\Login())->execute();
        });

        Route::add('/connexion', function () {
            (new \Auth\Controllers\Login\Login())->execute();
        });

        Route::add('/register', function () {
            (new \Auth\Controllers\Register\Register())->execute();
        });

        Route::add('/inscription', function () {
            (new \Auth\Controllers\Register\Register())->execute();
        });

        Route::add('/logout', function () {
            (new \Auth\Controllers\Logout\Logout())->execute();
        });

        Route::add('/deconnexion', function () {
            (new \Auth\Controllers\Logout\Logout())->execute();
        });

        Route::add('/forgot_password', function () {
            (new \Auth\Controllers\ForgotPassword\ForgotPassword())->execute();
        });

        Route::add('/reset_password', function () {
            (new \Auth\Controllers\ResetPassword\ResetPassword())->execute();
        });

        Route::add('/a-propos', function () {
            (new \SAE_PHP\controllers\APropos())->execute();
        });

        Route::add('/contact', function () {
            (new \SAE_PHP\controllers\Contact())->execute();
        });

        Route::add('/mentions-legales', function () {
            (new \SAE_PHP\controllers\MentionsLegales())->execute();
        });

        Route::add('/plan-site', function () {
            (new \SAE_PHP\controllers\PlanSite())->execute();
        });

        Route::add('/plan-du-site', function () {
            (new \SAE_PHP\controllers\PlanSite())->execute();
        });
    }
}