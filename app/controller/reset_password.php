<?php

namespace Auth\Controllers\ResetPassword;

use Includes\Database\DatabaseConnection;
use Auth\Model\User\UserRepository;
use Utils\Csrf;
use Utils\Template;

class ResetPassword
{
    public function execute(): void
    {
        $error = null;
        $token = $_POST['token'] ?? ($_GET['token'] ?? '');
        $user = null;

        if (empty($token)) {
            $error = 'Jeton de réinitialisation manquant ou invalide.';
        } else {
            $tokenHash = hash('sha256', $token);
            $userRepository = new UserRepository(DatabaseConnection::getInstance());
            $user = $userRepository->findUserByResetToken($tokenHash);

            if ($user === null) {
                $error = 'Le lien de réinitialisation est invalide ou a expiré.';
            } elseif (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
                $csrfToken = $_POST['csrf_token'] ?? '';
                $password = $_POST['password'] ?? '';
                $passwordConfirm = $_POST['password_confirm'] ?? '';

                if (!Csrf::validateToken($csrfToken)) {
                    $error = 'Jeton de sécurité invalide ou expiré.';
                } elseif (empty($password) || empty($passwordConfirm)) {
                    $error = 'Veuillez renseigner tous les champs obligatoires.';
                } elseif ($password !== $passwordConfirm) {
                    $error = 'Les mots de passe ne correspondent pas.';
                } elseif (strlen($password) < 12) {
                    $error = 'Le mot de passe doit comporter au moins 12 caractères.';
                } elseif (strlen($password) > 72) {
                    $error = 'Le mot de passe ne doit pas dépasser 72 caractères.';
                } elseif (!preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/[0-9]/', $password) || !preg_match('/[\W_]/', $password)) {
                    $error = 'Le mot de passe doit contenir au moins une majuscule, une minuscule, un chiffre et un caractère spécial.';
                } else {
                    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                    $updated = $userRepository->updatePassword($user->getId(), $passwordHash);

                    if ($updated) {
                        $userRepository->deletePasswordReset($tokenHash);
                        header('Location: index.php?action=login&reset=success');
                        exit;
                    } else {
                        $error = 'Une erreur est survenue lors de la mise à jour du mot de passe.';
                    }
                }
            }
        }

        Template::render('reset_password', [
            'title' => 'CyberLab - Nouveau mot de passe',
            'error' => $error,
            'token' => $token,
            'user' => $user
        ]);
    }
}
