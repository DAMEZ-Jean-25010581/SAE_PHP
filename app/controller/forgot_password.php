<?php

namespace Auth\Controllers\ForgotPassword;

use Auth\Controllers\RequestInput;
use Includes\Database\DatabaseConnection;
use Auth\Model\User\UserRepository;
use PDOException;
use SAE_PHP\models\Exceptions\UserException;
use Utils\Csrf;
use Utils\Template;

class ForgotPassword
{
    use RequestInput;

    public function execute(): void
    {
        $error = null;
        $successMessage = null;
        $identifier = '';

        if ($this->isPost()) {
            $identifier = trim($this->post('identifier'));

            if (!Csrf::validateToken($this->post('csrf_token'))) {
                $error = 'Jeton de sécurité invalide ou expiré.';
            } elseif ($identifier === '') {
                $error = 'Veuillez saisir votre pseudo ou votre adresse email.';
            } else {
                $userRepository = new UserRepository(DatabaseConnection::getInstance());
                $user = $userRepository->findByUsernameOrEmail($identifier);

                if ($user !== null) {
                    $token = bin2hex(random_bytes(32));
                    $tokenHash = hash('sha256', $token);
                    $expiresAt = time() + 900;

                    try {
                        $userRepository->createPasswordReset($user->getId(), $tokenHash, $expiresAt);
                        $this->sendResetEmail($user->getEmail(), $token);
                    } catch (UserException | PDOException $e) {
                        error_log($e->getMessage());
                    }
                }

                $successMessage = 'Si ce compte existe, un email contenant un lien de réinitialisation vient d\'être envoyé.';
            }
        }

        Template::render('forgot_password', [
            'title' => 'CyberLab - Récupération',
            'error' => $error,
            'successMessage' => $successMessage,
            'identifier' => $identifier
        ]);
    }

    private function setting(string $key, string $default): string
    {
        $file = dirname(__DIR__, 2) . '/config/config.local.php';
        $config = is_file($file) ? (array) require $file : [];

        if (isset($config[$key]) && is_string($config[$key]) && $config[$key] !== '') {
            return $config[$key];
        }

        $env = getenv($key);

        return $env !== false && $env !== '' ? $env : $default;
    }

    private function sendResetEmail(string $email, string $token): void
    {
        $baseUrl = rtrim($this->setting('APP_URL', 'http://localhost:8080'), '/');
        $resetLink = $baseUrl . '/reset_password?token=' . urlencode($token);
        $sender = $this->setting('MAIL_FROM', 'no-reply@' . (parse_url($baseUrl, PHP_URL_HOST) ?: 'localhost'));

        $subject = '=?UTF-8?B?' . base64_encode('CyberLab - Réinitialisation de votre mot de passe') . '?=';
        $message = "Bonjour,\r\n\r\n"
            . "Pour choisir un nouveau mot de passe, ouvrez ce lien (valable 15 minutes) :\r\n"
            . $resetLink . "\r\n\r\n"
            . "Si vous n'êtes pas à l'origine de cette demande, ignorez simplement cet email.\r\n";
        $headers = [
            'From'         => 'CyberLab <' . $sender . '>',
            'Content-Type' => 'text/plain; charset=UTF-8',
        ];

        if (!@mail($email, $subject, $message, $headers)) {
            error_log('Email de réinitialisation non envoyé à ' . $email . ' : ' . $resetLink);
        }
    }
}
