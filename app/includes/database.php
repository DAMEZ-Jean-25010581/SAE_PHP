<?php

namespace Includes\Database;

use PDO;
use PDOException;

/**
 * Connexion MySQL / MariaDB (singleton).
 *
 * Identifiants lus dans cet ordre :
 *  1. public/_assets/config/config.local.php  (sur alwaysdata, fichier non versionné)
 *  2. variables d'environnement DB_*    (en local, fournies par Docker)
 *
 * Le schéma de la base est dans BDD/mysql/*.sql (plus créé ici).
 */
class DatabaseConnection
{
    private static ?DatabaseConnection $instance = null;
    private ?PDO $pdo = null;

    private function __construct()
    {
        $config = self::loadConfig();

        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
            $config['DB_HOST'],
            $config['DB_PORT'],
            $config['DB_NAME']
        );

        try {
            $this->pdo = new PDO($dsn, $config['DB_USER'], $config['DB_PASS'], [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            // On ne montre pas le détail (hôte, utilisateur...) aux visiteurs
            error_log('Connexion BDD impossible : ' . $e->getMessage());
            http_response_code(500);
            die('Erreur de connexion à la base de données.');
        }
    }

    /**
     * @return array{DB_HOST: string, DB_PORT: string, DB_NAME: string, DB_USER: string, DB_PASS: string}
     */
    private static function loadConfig(): array
    {
        $file = __DIR__ . '/../config/config.local.php';
        $local = is_file($file) ? (array) require $file : [];

        $get = static function (string $key, string $default = '') use ($local): string {
            if (isset($local[$key])) {
                return (string) $local[$key];
            }
            $env = getenv($key);
            return $env !== false ? $env : $default;
        };

        return [
            'DB_HOST' => $get('DB_HOST', 'db'),
            'DB_PORT' => $get('DB_PORT', '3306'),
            'DB_NAME' => $get('DB_NAME'),
            'DB_USER' => $get('DB_USER'),
            'DB_PASS' => $get('DB_PASS'),
        ];
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->pdo;
    }

    public static function setConnection(PDO $customPdo): void
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        self::$instance->pdo = $customPdo;
    }
}
