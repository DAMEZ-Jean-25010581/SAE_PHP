<?php

namespace Auth\Controllers\Logout;

use Utils\Csrf;

class Logout
{
    public function execute(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST' || !Csrf::validateToken($_POST['csrf_token'] ?? '')) {
            header('Location: index.php');
            exit;
        }

        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        session_destroy();

        header('Location: index.php?action=login&logout=success');
        exit;
    }
}
