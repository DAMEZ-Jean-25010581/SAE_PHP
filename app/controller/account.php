<?php

namespace Auth\Controllers\Account;

use Auth\Controllers\Logout\Logout;
use Auth\Controllers\Register\Register;
use Auth\Controllers\RequestInput;
use Includes\Database\DatabaseConnection;
use Auth\Model\User\UserRepository;
use PDOException;
use SAE_PHP\models\Exceptions\UserException;
use Utils\Csrf;
use Utils\SessionHelpers;
use Utils\Template;

class Account
{
    use RequestInput;

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

        if ($this->isPost()) {
            if (!Csrf::validateToken($this->post('csrf_token'))) {
                $error = 'Jeton de sécurité invalide ou expiré.';
            } else {
                try {
                    $this->handleForm($userRepository, $userId, $this->post('form'));
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
            Logout::destroySession();
            header('Location: /login');
            exit;
        }

        Template::render('account', [
            'title'          => 'CyberLab - Mon compte',
            'user'           => $user,
            'error'          => $error,
            'successMessage' => self::SUCCESS_MESSAGES[$this->query('updated')] ?? null,
            'csrfToken'      => Csrf::generateToken(),
        ]);
    }

    private function handleForm(UserRepository $userRepository, int $userId, string $form): void
    {
        switch ($form) {
            case 'username':
                $username = trim($this->post('username'));

                if ($username !== '' && !preg_match(Register::USERNAME_PATTERN, $username)) {
                    throw new UserException(Register::USERNAME_RULES);
                }

                if ($username === ($_SESSION['user']['username'] ?? null)) {
                    throw new UserException('C\'est déjà votre pseudo actuel.');
                }

                $userRepository->updateUsername($userId, $username);
                $_SESSION['user']['username'] = $username;
                $this->redirect('username');

            case 'email':
                $email = trim($this->post('email'));

                if (strlen($email) > 100) {
                    throw new UserException('L\'adresse email saisie est invalide.');
                }

                $userRepository->checkPassword($userId, $this->post('current_password'));
                $userRepository->updateEmail($userId, $email);
                $_SESSION['user']['email'] = $email;
                $this->redirect('email');

            case 'password':
                $userRepository->checkPassword($userId, $this->post('current_password'));
                $userRepository->updatePassword($userId, $this->post('password'), $this->post('password_confirm'));
                session_regenerate_id(true);
                $this->redirect('password');

            case 'delete':
                $userRepository->checkPassword($userId, $this->post('current_password'));
                $userRepository->deleteUser($userId);
                Logout::destroySession();
                header('Location: /');
                exit;
        }
    }

    private function redirect(string $updated): never
    {
        header('Location: /account?updated=' . $updated);
        exit;
    }
}
