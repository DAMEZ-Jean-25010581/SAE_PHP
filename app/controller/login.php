<?php

namespace Auth\Controllers\Login;

use Includes\Database\DatabaseConnection;
use Auth\Model\User\UserRepository;
use PDOException;
use SAE_PHP\models\Exceptions\UserException;
use Utils\Csrf;
use Utils\SessionHelpers;
use Utils\Template;

class Login
{
    private const MAX_ATTEMPTS = 5;
    private const LOCK_SECONDS = 900;

    public function execute(): void
    {
        if (SessionHelpers::isLogin()) {
            header('Location: /');
            exit;
        }

        $error = null;

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            $csrfToken = $_POST['csrf_token'] ?? '';
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $lockRemaining = $this->lockRemaining($email);

            if (!Csrf::validateToken($csrfToken)) {
                $error = 'Jeton de sécurité invalide ou expiré.';
            } elseif ($lockRemaining > 0) {
                $error = 'Trop de tentatives de connexion. Réessayez dans ' . (int) ceil($lockRemaining / 60) . ' minute(s).';
            } else {
                try {
                    $userRepository = new UserRepository(DatabaseConnection::getInstance());
                    $user = $userRepository->login($email, $password);

                    $this->clearAttempts($email);
                    session_regenerate_id(true);

                    $_SESSION['user'] = [
                        'id'       => $user->getId(),
                        'username' => $user->getUsername(),
                        'email'    => $user->getEmail(),
                    ];

                    header('Location: /');
                    exit;
                } catch (UserException $e) {
                    if ($email !== '' && $password !== '') {
                        $this->recordFailure($email);
                    }
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

    private function attemptsFile(string $email): string
    {
        $directory = sys_get_temp_dir() . '/cyberlab_login_attempts';

        if (!is_dir($directory)) {
            mkdir($directory, 0700, true);
        }

        $key = hash('sha256', strtolower($email) . '|' . ($_SERVER['REMOTE_ADDR'] ?? ''));

        return $directory . '/' . $key . '.json';
    }

    private function readAttempts(string $email): array
    {
        $file = $this->attemptsFile($email);
        $data = is_file($file) ? json_decode((string) file_get_contents($file), true) : null;

        if (!is_array($data) || !isset($data['count'], $data['first'])) {
            return ['count' => 0, 'first' => time()];
        }

        return $data;
    }

    private function lockRemaining(string $email): int
    {
        $attempts = $this->readAttempts($email);

        if ($attempts['count'] < self::MAX_ATTEMPTS) {
            return 0;
        }

        return max(0, $attempts['first'] + self::LOCK_SECONDS - time());
    }

    private function recordFailure(string $email): void
    {
        $attempts = $this->readAttempts($email);

        if (time() - $attempts['first'] > self::LOCK_SECONDS) {
            $attempts = ['count' => 0, 'first' => time()];
        }

        $attempts['count']++;

        file_put_contents($this->attemptsFile($email), json_encode($attempts), LOCK_EX);
    }

    private function clearAttempts(string $email): void
    {
        $file = $this->attemptsFile($email);

        if (is_file($file)) {
            unlink($file);
        }
    }
}
