<?php

namespace Auth\Controllers\Login;

use Auth\Controllers\RequestInput;
use Includes\Database\DatabaseConnection;
use Auth\Model\User\UserRepository;
use PDOException;
use SAE_PHP\models\Exceptions\UserException;
use Utils\Csrf;
use Utils\SessionHelpers;
use Utils\Template;

class Login
{
    use RequestInput;

    private const MAX_ATTEMPTS = 5;
    private const LOCK_SECONDS = 900;

    public function execute(): void
    {
        if (SessionHelpers::isLogin()) {
            header('Location: /');
            exit;
        }

        $error = null;
        $identifier = '';

        if ($this->isPost()) {
            $identifier = trim($this->post('identifier'));
            $password = $this->post('password');
            $lockRemaining = $this->lockRemaining($identifier);

            if (!Csrf::validateToken($this->post('csrf_token'))) {
                $error = 'Jeton de sécurité invalide ou expiré.';
            } elseif ($lockRemaining > 0) {
                $error = 'Trop de tentatives de connexion. Réessayez dans ' . (int) ceil($lockRemaining / 60) . ' minute(s).';
            } else {
                try {
                    $userRepository = new UserRepository(DatabaseConnection::getInstance());
                    $user = $userRepository->login($identifier, $password);

                    $this->clearAttempts($identifier);
                    session_regenerate_id(true);

                    $_SESSION['user'] = [
                        'id'       => $user->getId(),
                        'username' => $user->getUsername(),
                        'email'    => $user->getEmail(),
                    ];

                    header('Location: /');
                    exit;
                } catch (UserException $e) {
                    if ($identifier !== '' && $password !== '') {
                        $this->recordFailure($identifier);
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
            'error' => $error,
            'identifier' => $identifier
        ]);
    }

    private function attemptsFile(string $identifier): string
    {
        $directory = sys_get_temp_dir() . '/cyberlab_login_attempts';

        if (!is_dir($directory)) {
            mkdir($directory, 0700, true);
        }

        $key = hash('sha256', strtolower($identifier) . '|' . ($_SERVER['REMOTE_ADDR'] ?? ''));

        return $directory . '/' . $key . '.json';
    }

    private function readAttempts(string $identifier): array
    {
        $file = $this->attemptsFile($identifier);
        $data = is_file($file) ? json_decode((string) file_get_contents($file), true) : null;

        if (!is_array($data) || !isset($data['count'], $data['first'])) {
            return ['count' => 0, 'first' => time()];
        }

        return $data;
    }

    private function lockRemaining(string $identifier): int
    {
        $attempts = $this->readAttempts($identifier);

        if ($attempts['count'] < self::MAX_ATTEMPTS) {
            return 0;
        }

        return max(0, $attempts['first'] + self::LOCK_SECONDS - time());
    }

    private function recordFailure(string $identifier): void
    {
        $attempts = $this->readAttempts($identifier);

        if (time() - $attempts['first'] > self::LOCK_SECONDS) {
            $attempts = ['count' => 0, 'first' => time()];
        }

        $attempts['count']++;

        file_put_contents($this->attemptsFile($identifier), json_encode($attempts), LOCK_EX);
    }

    private function clearAttempts(string $identifier): void
    {
        $file = $this->attemptsFile($identifier);

        if (is_file($file)) {
            unlink($file);
        }
    }
}
