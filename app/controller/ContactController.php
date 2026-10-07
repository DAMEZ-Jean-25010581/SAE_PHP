<?php
namespace SAE_PHP\controllers;

use Utils\Template;
use Utils\Csrf;
use SAE_PHP\BDD\mysql\DatabaseConnection;

class ContactController
{

    public function contact(): void
    {
        $erreurs = [];
        $succes = false;
        $anciens = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

             //vérification csrf pour le owasp
            if (!Csrf::validateToken($_POST['csrf_token'] ?? '')) {
                http_response_code(403);
                exit('Requête invalide.');
            }

            //recupération données du formulaire
            $email = trim($_POST['email'] ?? '');
            $objet = trim($_POST['objet'] ?? '');
            $message = trim($_POST['message'] ?? '');

            $anciens = ['email' => $email, 'objet' => $objet, 'message' => $message];

            // validation des données
            if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 100) {
                $erreurs[] = "L'adresse e-mail n'est pas valide.";
            }
            if ($objet === '' || mb_strlen($objet) > 50) {
                $erreurs[] = "L'objet est obligatoire.(50 caractères maximum)";
            }
            if (preg_match('/[\r\n]/', $email . $objet)) {
                $erreurs[] = "Caractères non autorisés.";
            }
            if ($message === '' || mb_strlen($message) > 2000) {
                $erreurs[] = "Le message est obligatoire.(2000 caractères maximum)";
            }
            //limite envoie par minute
            if (isset($_SESSION['dernier_contact']) && time() - $_SESSION['dernier_contact'] < 60) {
                $erreurs[] = "Veuillez patienter avant d'envoyer un nouveau message.";
            }
            //BDD
            if (empty($erreurs)) {
                try {
                    $pdo = DatabaseConnection::getInstance()->getConnection();
                    $stmt = $pdo->prepare('INSERT INTO ContactMessage_ (email, objet, message) VALUES (:email, :objet, :message)');
                    $stmt->execute([
                        ':email'   => $email,
                        ':objet'   => $objet,
                        ':message' => $message,
                    ]);

                    $_SESSION['dernier_contact'] = time();
                    $succes = true;
                    $anciens = [];
                } catch (\PDOException $e) {
                    error_log($e->getMessage());
                    $erreurs[] = "Une erreur est survenue, réessayez plus tard.";
                }
            }
        }

        // affichage vue contact
        Template::render('contact', [
            'title'   => 'CyberLab - Contact',
            'erreurs' => $erreurs,
            'succes'  => $succes,
            'anciens' => $anciens,
        ]);
    }
}

