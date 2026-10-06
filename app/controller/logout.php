<?php

namespace Auth\Controllers\Logout;

use Auth\Controllers\RequestInput;
use Utils\Csrf;

class Logout
{
    use RequestInput;

    public function execute(): void
    {
        if (!$this->isPost() || !Csrf::validateToken($this->post('csrf_token'))) {
            header('Location: /');
            exit;
        }

        self::destroySession();

        header('Location: /login?logout=success');
        exit;
    }

    public static function destroySession(): void
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();
    }
}
