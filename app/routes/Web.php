<?php

namespace routes;

use routes\base\Route;
use Utils\SessionHelpers;
use Utils\Template;

class Web
{
    public function __construct()
    {
        Route::add('/', function () {
            (new \SAE_PHP\controllers\HomeController())->execute();
        });

        Route::add('/home', function () {
            (new \SAE_PHP\controllers\HomeController())->execute();
        });

        Route::add('/login', function () {
            (new \Auth\Controllers\Login\Login())->execute();
        });

        Route::add('/register', function () {
            (new \Auth\Controllers\Register\Register())->execute();
        });

        Route::add('/logout', function () {
            (new \Auth\Controllers\Logout\Logout())->execute();
        });

        Route::add('/forgot_password', function () {
            (new \Auth\Controllers\ForgotPassword\ForgotPassword())->execute();
        });

        Route::add('/reset_password', function () {
            (new \Auth\Controllers\ResetPassword\ResetPassword())->execute();
        });

        Route::add('/account', function () {
            (new \Auth\Controllers\Account\Account())->execute();
        });

        Route::add('/a-propos', function () {
            Template::render('aPropos', [
                'title' => 'CyberLab - À propos'
            ]);
        });

        Route::add('/contact', function () {
            Template::render('contact', [
                'title' => 'CyberLab - Contact'
            ]);
        });

        Route::add('/mentions-legales', function () {
            Template::render('mentions-legales', [
                'title' => 'CyberLab - Mentions légales'
            ]);
        });

        Route::add('/plan-site', function () {
            Template::render('plan-site', [
                'title' => 'CyberLab - Plan du site'
            ]);
        });

        Route::add('/plan-du-site', function () {
            Template::render('plan-site', [
                'title' => 'CyberLab - Plan du site'
            ]);
        });
    }
}
