<?php

namespace Auth\Model\User;

use PDO;

class User
{
    public function __construct(
        private ?int $id,
        private string $username,
        private string $email,
        private ?string $passwordHash = null
    ) {}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPasswordHash(): ?string
    {
        return $this->passwordHash;
    }
}

class UserRepository
{
    public function __construct(private mixed $connection) {}

    private function getPdo(): PDO
    {
        if ($this->connection instanceof PDO) {
            return $this->connection;
        }

        if (is_object($this->connection) && method_exists($this->connection, 'getConnection')) {
            return $this->connection->getConnection();
        }

        throw new \RuntimeException("Connexion à la base de données invalide.");
    }

    public function findByUsername(string $username): ?User
    {
        $statement = $this->getPdo()->prepare(
            'SELECT user_id, user_name, email, password_hash 
             FROM User_ 
             WHERE user_name = :username'
        );
        $statement->execute(['username' => $username]);
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return new User(
            (int) $row['user_id'],
            $row['user_name'],
            $row['email'],
            $row['password_hash']
        );
    }

    public function findByEmail(string $email): ?User
    {
        $statement = $this->getPdo()->prepare(
            'SELECT user_id, user_name, email, password_hash 
             FROM User_ 
             WHERE email = :email'
        );
        $statement->execute(['email' => $email]);
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return new User(
            (int) $row['user_id'],
            $row['user_name'],
            $row['email'],
            $row['password_hash']
        );
    }

    public function exists(string $username, string $email): bool
    {
        $statement = $this->getPdo()->prepare(
            'SELECT COUNT(*) 
             FROM User_ 
             WHERE user_name = :username OR email = :email'
        );
        $statement->execute([
            'username' => $username,
            'email'    => $email,
        ]);

        return (int) $statement->fetchColumn() > 0;
    }

    public function createUser(string $username, string $email, string $passwordHash): bool
    {
        $idStmt = $this->getPdo()->query('SELECT COALESCE(MAX(user_id), 0) + 1 FROM User_');
        $nextId = (int) $idStmt->fetchColumn();

        $statement = $this->getPdo()->prepare(
            'INSERT INTO User_ (user_id, user_name, email, password_hash) 
             VALUES (:user_id, :username, :email, :password_hash)'
        );

        return $statement->execute([
            'user_id'       => $nextId,
            'username'      => $username,
            'email'         => $email,
            'password_hash' => $passwordHash,
        ]);
    }
}
