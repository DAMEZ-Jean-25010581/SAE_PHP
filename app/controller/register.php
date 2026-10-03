<?php

namespace Auth\Controllers\Register;

use Includes\Database\DatabaseConnection;
use Auth\Model\User\UserRepository;
use PDOException;
use SAE_PHP\models\Exceptions\UserException;
use Utils\Csrf;
use Utils\Template;

class Register
{
    public function execute(): void
    {
        $error = null;

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            $csrfToken = $_POST['csrf_token'] ?? '';
            $username = trim($_POST['login'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $passwordConfirm = $_POST['password_confirm'] ?? '';

            if (!Csrf::validateToken($csrfToken)) {
                $error = 'Jeton de sécurité invalide ou expiré.';
            } elseif ($username !== '' && !preg_match('/^[a-zA-Z0-9_\-\.]{3,30}$/', $username)) {
                $error = 'L\'identifiant doit comporter entre 3 et 30 caractères (lettres, chiffres, tirets, underscores).';
            } elseif (strlen($email) > 100) {
                $error = 'L\'adresse email saisie est invalide.';
            } else {
                try {
                    $userRepository = new UserRepository(DatabaseConnection::getInstance());
                    $userRepository->createUser($username, $email, $password, $passwordConfirm);

                    header('Location: index.php?action=login&registered=success');
                    exit;
                } catch (UserException $e) {
                    $error = $e->getMessage();
                } catch (PDOException $e) {
                    error_log($e->getMessage());
                    $error = 'Une erreur est survenue lors de l\'enregistrement.';
                }
            }
        }

        Template::render('register', [
            'title' => 'CyberLab - Inscription',
            'error' => $error
        ]);
    }
}
