<?php
namespace SAE_PHP\includes;

class DatabaseConnection {

    public static function getInstance(): \PDO
    {
        static $pdo = null;

    if ($pdo === null) {
        try {
            // Connexion à la base de données.
            $dsn = 'mysql:host=localhost;dbname=my_dbname';
            $pdo = new \PDO($dsn, 'mysql_username', 'mysql_password');

            // Codage de caractères.
            $pdo->exec('SET CHARACTER SET utf8');

            // Gestion des erreurs sous forme d'exceptions.
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            // Affichage de l'erreur.
            die('Erreur : ' . $e->getMessage());
        }
    }

    return $pdo;
}
}