<?php

namespace Auth\Controllers\Login;

use Includes\Database\DatabaseConnection;
use Auth\Model\User\UserRepository;
use PDOException;
use SAE_PHP\models\Exceptions\UserException;
use Utils\Csrf;
use Utils\Template;

class Login
{
    public function execute(): void
    {
        $error = null;

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            $csrfToken = $_POST['csrf_token'] ?? '';
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if (!Csrf::validateToken($csrfToken)) {
                $error = 'Jeton de sécurité invalide ou expiré.';
            } else {
                try {
                    $userRepository = new UserRepository(DatabaseConnection::getInstance());
                    $user = $userRepository->login($email, $password);

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
                } catch (UserException $e) {
                    $error = $e->getMessage();
                } catch (PDOException $e) {
                    error_log($e->getMessage());
                    $error = 'Une erreur est survenue lors de la connexion.';
                }
            }
        }

        Template::render('login', [
            'title' => 'CyberLab - Connexion',
            'error' => $error
        ]);
    }
}
