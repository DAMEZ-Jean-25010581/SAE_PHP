<?php

namespace Auth\Controllers\ResetPassword;

use Auth\Controllers\RequestInput;
use Includes\Database\DatabaseConnection;
use Auth\Model\User\UserRepository;
use PDOException;
use SAE_PHP\models\Exceptions\UserException;
use Utils\Csrf;
use Utils\Template;

class ResetPassword
{
    use RequestInput;

    public function execute(): void
    {
        $error = null;
        $token = $this->post('token') ?: $this->query('token');
        $user = null;

        if ($token === '') {
            $error = 'Jeton de réinitialisation manquant ou invalide.';
        } else {
            $tokenHash = hash('sha256', $token);
            $userRepository = new UserRepository(DatabaseConnection::getInstance());
            $user = $userRepository->findUserByResetToken($tokenHash);

            if ($user === null) {
                $error = 'Le lien de réinitialisation est invalide ou a expiré.';
            } elseif ($this->isPost()) {
                if (!Csrf::validateToken($this->post('csrf_token'))) {
                    $error = 'Jeton de sécurité invalide ou expiré.';
                } else {
                    try {
                        $userRepository->updatePassword($user->getId(), $this->post('password'), $this->post('password_confirm'));
                        $userRepository->deletePasswordReset($tokenHash);

                        header('Location: /login?reset=success');
                        exit;
                    } catch (UserException $e) {
                        $error = $e->getMessage();
                    } catch (PDOException $e) {
                        error_log($e->getMessage());
                        $error = 'Une erreur est survenue lors de la mise à jour du mot de passe.';
                    }
                }
            }
        }

        Template::render('reset_password', [
            'title' => 'CyberLab - Nouveau mot de passe',
            'error' => $error,
            'token' => $token,
            'user'  => $user
        ]);
    }
}
