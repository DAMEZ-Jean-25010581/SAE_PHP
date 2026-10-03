<?php

namespace Auth\Controllers\ForgotPassword;

use Includes\Database\DatabaseConnection;
use Auth\Model\User\UserRepository;
use PDOException;
use SAE_PHP\models\Exceptions\UserException;
use Utils\Csrf;

class ForgotPassword
{
    public function execute(): void
    {
        $error = null;
        $successMessage = null;
        $resetLink = null;

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            $csrfToken = $_POST['csrf_token'] ?? '';
            $identifier = trim($_POST['identifier'] ?? '');

            if (!Csrf::validateToken($csrfToken)) {
                $error = 'Jeton de sécurité invalide ou expiré.';
            } elseif (empty($identifier)) {
                $error = 'Veuillez saisir votre identifiant ou votre adresse email.';
            } else {
                $userRepository = new UserRepository(DatabaseConnection::getInstance());
                $user = $userRepository->findByUsernameOrEmail($identifier);

                if ($user !== null) {
                    $token = bin2hex(random_bytes(32));
                    $tokenHash = hash('sha256', $token);
                    $expiresAt = time() + 900;

                    try {
                        $userRepository->createPasswordReset($user->getId(), $tokenHash, $expiresAt);
                        $resetLink = 'index.php?action=reset_password&token=' . urlencode($token);
                    } catch (UserException | PDOException $e) {
                        error_log($e->getMessage());
                    }
                }

                $successMessage = 'Si ce compte existe, un lien de réinitialisation a été généré.';
            }
        }

        require_once __DIR__ . '/../views/forgot_password.php';
    }
}
