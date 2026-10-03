<?php

namespace Auth\Controllers\Login;

use Includes\Database\DatabaseConnection;
use Auth\Model\User\UserRepository;
use Utils\Csrf;
use Utils\Template;

class Login
{
    public function execute(): void
    {
        $error = null;

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            $csrfToken = $_POST['csrf_token'] ?? '';
            $username = trim($_POST['login'] ?? '');
            $password = $_POST['password'] ?? '';

            if (!Csrf::validateToken($csrfToken)) {
                $error = 'Jeton de sécurité invalide ou expiré.';
            } elseif (empty($username) || empty($password)) {
                $error = 'Veuillez renseigner tous les champs.';
            } else {
                $userRepository = new UserRepository(DatabaseConnection::getInstance());
                $user = $userRepository->findByUsername($username);

                if ($user !== null && password_verify($password, $user->getPasswordHash())) {
                    if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }

                    session_regenerate_id(true);

                    $_SESSION['user'] = [
                        'id'       => $user->getId(),
                        'username' => $user->getUsername(),
                        'email'    => $user->getEmail(),
                    ];

                    header('Location: index.php');
                    exit;
                } else {
                    $error = 'Identifiant ou mot de passe incorrect.';
                }
            }
        }

        Template::render('login', [
            'title' => 'CyberLab - Connexion',
            'error' => $error
        ]);
    }
}
