<?php

namespace Auth\Controllers\Register;

use Includes\Database\DatabaseConnection;
use Auth\Model\User\UserRepository;

class Register
{
    public function execute(): void
    {
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['login'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $passwordConfirm = $_POST['password_confirm'] ?? '';

            if (empty($username) || empty($email) || empty($password) || empty($passwordConfirm)) {
                $error = 'Veuillez renseigner tous les champs obligatoires.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'L\'adresse email saisie est invalide.';
            } elseif ($password !== $passwordConfirm) {
                $error = 'Les mots de passe ne correspondent pas.';
            } elseif (strlen($password) < 4) {
                $error = 'Le mot de passe doit comporter au moins 4 caractères.';
            } else {
                $userRepository = new UserRepository(DatabaseConnection::getInstance());

                if ($userRepository->exists($username, $email)) {
                    $error = 'Cet identifiant ou cette adresse email est déjà utilisé.';
                } else {
                    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                    $success = $userRepository->createUser($username, $email, $passwordHash);

                    if ($success) {
                        header('Location: index.php?action=login&registered=success');
                        exit;
                    } else {
                        $error = 'Une erreur est survenue lors de l\'enregistrement.';
                    }
                }
            }
        }

        require_once __DIR__ . '/../views/register.php';
    }
}
