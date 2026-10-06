<?php
namespace SAE_PHP\controllers;

class ContactController
{

    public function contact(): void
    {
        $erreurs = [];
        $succes = false;
        $anciens = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $objet = trim($_POST['objet'] ?? '');
            $message = trim($_POST['message'] ?? '');

            $anciens = ['email' => $email, 'objet' => $objet, 'message' => $message];

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $erreurs[] = "L'adresse e-mail n'est pas valide.";
            }
            if ($objet === '') {
                $erreurs[] = "L'objet est obligatoire.";
            }
            if ($message === '') {
                $erreurs[] = "Le message est obligatoire.";
            }

            if (empty($erreurs)) {
                // ici : mail() ou INSERT en base
                $succes = true;
                $anciens = [];
            }
        }

        // afficher la vue contact avec $erreurs, $succes, $anciens
    }
}

