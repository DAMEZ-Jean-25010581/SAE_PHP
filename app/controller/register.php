<?php

namespace Auth\Controllers\Register;

use Auth\Controllers\RequestInput;
use Includes\Database\DatabaseConnection;
use Auth\Model\User\UserRepository;
use PDOException;
use SAE_PHP\models\Exceptions\UserException;
use Utils\Csrf;
use Utils\SessionHelpers;
use Utils\Template;

class Register
{
    use RequestInput;

    public const USERNAME_PATTERN = '/^[a-zA-Z0-9_\-\.]{3,30}$/';
    public const USERNAME_RULES = 'Le pseudo doit comporter entre 3 et 30 caractères (lettres, chiffres, tirets, points, underscores).';

    public function execute(): void
    {
        if (SessionHelpers::isLogin()) {
            header('Location: /');
            exit;
        }

        $error = null;
        $username = '';
        $email = '';

        if ($this->isPost()) {
            $username = trim($this->post('username'));
            $email = trim($this->post('email'));

            if (!Csrf::validateToken($this->post('csrf_token'))) {
                $error = 'Jeton de sécurité invalide ou expiré.';
            } elseif ($username !== '' && !preg_match(self::USERNAME_PATTERN, $username)) {
                $error = self::USERNAME_RULES;
            } elseif (strlen($email) > 100) {
                $error = 'L\'adresse email saisie est invalide.';
            } else {
                try {
                    $userRepository = new UserRepository(DatabaseConnection::getInstance());
                    $userRepository->createUser($username, $email, $this->post('password'), $this->post('password_confirm'));

                    header('Location: /login?registered=success');
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
            'title'    => 'CyberLab - Inscription',
            'error'    => $error,
            'username' => $username,
            'email'    => $email
        ]);
    }
}
