<?php

namespace Auth\Controllers\Register;

use Includes\Database\DatabaseConnection;
use Auth\Model\User\UserRepository;
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
            } elseif (empty($username) || empty($email) || empty($password) || empty($passwordConfirm)) {
                $error = 'Veuillez renseigner tous les champs obligatoires.';
            } elseif (!preg_match('/^[a-zA-Z0-9_\-\.]{3,30}$/', $username)) {
                $error = 'L\'identifiant doit comporter entre 3 et 30 caractères (lettres, chiffres, tirets, underscores).';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 100) {
                $error = 'L\'adresse email saisie est invalide.';
            } elseif ($password !== $passwordConfirm) {
                $error = 'Les mots de passe ne correspondent pas.';
            } elseif (strlen($password) < 12) {
                $error = 'Le mot de passe doit comporter au moins 12 caractères.';
            } elseif (strlen($password) > 72) {
                $error = 'Le mot de passe ne doit pas dépasser 72 caractères.';
            } elseif (!preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/[0-9]/', $password) || !preg_match('/[\W_]/', $password)) {
                $error = 'Le mot de passe doit contenir au moins une majuscule, une minuscule, un chiffre et un caractère spécial.';
            } else {
                $userRepository = new UserRepository(DatabaseConnection::getInstance());

                if (
                    $userRepository->usernameExists($username)
                    || $userRepository->emailExists($email)
                ) {
                    $error = 'Cet identifiant ou cette adresse email est déjà utilisé.';
                } else {
                    $userRepository->createUser(
                        $username,
                        $email,
                        $password,
                        $passwordConfirm
                    );

                    header('Location: index.php?action=login&registered=success');
                    exit;
                }
            }
        }

        Template::render('register', [
            'title' => 'CyberLab - Inscription',
            'error' => $error
        ]);
    }
}
