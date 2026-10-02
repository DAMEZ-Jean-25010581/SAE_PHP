<?php

namespace Includes\Database;

use PDO;
use PDOException;

class DatabaseConnection
{
    private static ?DatabaseConnection $instance = null;
    private ?PDO $pdo = null;

    private function __construct()
    {
        $dataDir = __DIR__ . '/../../data';
        if (!is_dir($dataDir)) {
            mkdir($dataDir, 0777, true); //probleme de sécurité, à revoir
        }

        $dbFile = $dataDir . '/database.sqlite';

        try {
            $this->pdo = new PDO('sqlite:' . $dbFile);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            $this->pdo->exec("
                CREATE TABLE IF NOT EXISTS User_ (
                    user_id INT PRIMARY KEY,
                    user_name VARCHAR(50) NOT NULL UNIQUE,
                    email VARCHAR(100) NOT NULL UNIQUE,
                    password_hash VARCHAR(255) NOT NULL
                );
            ");

            $this->pdo->exec("
                CREATE TABLE IF NOT EXISTS PasswordReset_ (
                    reset_id INTEGER PRIMARY KEY AUTOINCREMENT,
                    user_id INTEGER NOT NULL,
                    token_hash VARCHAR(64) NOT NULL UNIQUE,
                    expires_at INTEGER NOT NULL
                );
            ");

            $checkStmt = $this->pdo->query("SELECT COUNT(*) FROM User_ WHERE user_name = 'wanis'");
            if ((int) $checkStmt->fetchColumn() === 0) {
                $hash = '$2y$10$OfMA8xv7SzCLO6E1FVB8u.SgKJGRVotmabJHYGLwZ8/Rigc4Vm5ba';
                $this->pdo->exec("INSERT INTO User_ (user_id, user_name, email, password_hash) VALUES ((SELECT COALESCE(MAX(user_id), 0) + 1 FROM User_), 'wanis', 'wanis@admin.local', '$hash')");
            }
        } catch (PDOException $e) {
            die("Erreur de connexion : " . $e->getMessage());
        }
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
