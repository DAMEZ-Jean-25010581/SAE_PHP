<?php

namespace Auth\Controllers\Account;

use Includes\Database\DatabaseConnection;
use Auth\Model\User\UserRepository;
use PDOException;
use SAE_PHP\models\Exceptions\UserException;
use Utils\Csrf;
use Utils\SessionHelpers;
use Utils\Template;

class Account
{
    private const SUCCESS_MESSAGES = [
        'username' => 'Votre pseudo a bien été modifié.',
        'email'    => 'Votre adresse email a bien été modifiée.',
        'password' => 'Votre mot de passe a bien été modifié.',
    ];

    public function execute(): void
    {
        if (!SessionHelpers::isLogin()) {
            header('Location: /login');
            exit;
        }

        $userId = (int) $_SESSION['user']['id'];
        $userRepository = new UserRepository(DatabaseConnection::getInstance());
        $error = null;

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            if (!Csrf::validateToken($_POST['csrf_token'] ?? '')) {
                $error = 'Jeton de sécurité invalide ou expiré.';
            } else {
                try {
                    $this->handleForm($userRepository, $userId, $_POST['form'] ?? '');
                } catch (UserException $e) {
                    $error = $e->getMessage();
                } catch (PDOException $e) {
                    error_log($e->getMessage());
                    $error = 'Une erreur est survenue lors de la mise à jour de votre compte.';
                }
            }
        }

        $user = $userRepository->findById($userId);

        if ($user === null) {
            $this->destroySession();
            header('Location: /login');
            exit;
        }

        Template::render('account', [
            'title'          => 'CyberLab - Mon compte',
            'user'           => $user,
            'error'          => $error,
            'successMessage' => self::SUCCESS_MESSAGES[$_GET['updated'] ?? ''] ?? null,
            'csrfToken'      => Csrf::generateToken(),
        ]);
    }

    private function handleForm(UserRepository $userRepository, int $userId, string $form): void
    {
        switch ($form) {
            case 'username':
                $username = trim($_POST['username'] ?? '');

                if ($username !== '' && !preg_match('/^[a-zA-Z0-9_\-\.]{3,30}$/', $username)) {
                    throw new UserException('Le pseudo doit comporter entre 3 et 30 caractères (lettres, chiffres, tirets, points, underscores).');
                }

                if ($username === ($_SESSION['user']['username'] ?? null)) {
                    throw new UserException('C\'est déjà votre pseudo actuel.');
                }

                $userRepository->updateUsername($userId, $username);
                $_SESSION['user']['username'] = $username;
                $this->redirect('username');

            case 'email':
                $email = trim($_POST['email'] ?? '');

                if (strlen($email) > 100) {
                    throw new UserException('L\'adresse email saisie est invalide.');
                }

                $userRepository->checkPassword($userId, $_POST['current_password'] ?? '');
                $userRepository->updateEmail($userId, $email);
                $_SESSION['user']['email'] = $email;
                $this->redirect('email');

            case 'password':
                $userRepository->checkPassword($userId, $_POST['current_password'] ?? '');
                $userRepository->updatePassword($userId, $_POST['password'] ?? '', $_POST['password_confirm'] ?? '');
                session_regenerate_id(true);
                $this->redirect('password');

            case 'delete':
                $userRepository->checkPassword($userId, $_POST['current_password'] ?? '');
                $userRepository->deleteUser($userId);
                $this->destroySession();
                header('Location: /');
                exit;
        }
    }

    private function redirect(string $updated): never
    {
        header('Location: /account?updated=' . $updated);
        exit;
    }

    private function destroySession(): void
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
