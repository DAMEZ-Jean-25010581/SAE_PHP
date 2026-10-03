<?php

namespace Auth\Model\User;

use PDO;

class User
{
    public function __construct(
        private ?int $id,
        private string $username,
        private string $email,
        private ?string $passwordHash = null,
        private int $nbPoints = 0,
        private float $progression = 0.0
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

    public function getPoints(): int
    {
        return $this->nbPoints;
    }

    public function getProgression(): float
    {
        retun $this->progression;
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
            'SELECT *
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
            $row['password_hash'],
            $row['nb_points'],
            $row['progression']
        );
    }

    public function findByEmail(string $email): ?User
    {
        $statement = $this->getPdo()->prepare(
            'SELECT * 
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
            $row['password_hash'],
            $row['nb_points'],
            $row['progression']
        );
    }

    public function findById(int $userId): ?User
    {
        $statetment = $this->getPdo()->prepare(
            'SELECT *
            FROM User_
            WHERE user_id = :userId'
        );
        $statement->execute(['userId' => $userId]);
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return new User(
            (int) $row['user_id'],
            $row['user_name'],
            $row['email'],
            $row['password_hash'],
            $row['nb_points'],
            $row['progression']
        );
    }

    public function usernameExists(string $username): bool
    {
        return $this->findByUsername($username) !== null;
    }

    public function emailExists(string $email): bool
    {
        return $this->findByEmail($email) !== null;
    }

    public function idExists(int $userId): bool
    {
        return $this->findById($userId) !== null;
    }

    public function createUser(string $username, string $email, string $passwordHash): void
    {
        if ($this->usernameExists($username)){
            throw UserException::usernameAlreadyExists();
        }
        if ($this->emailExists($email)){
            throw UserException::emailAlreadyExists();
        }
        $statement = $this->getPdo()->prepare(
            'INSERT INTO User_ (user_name, email, password_hash) 
            VALUES (:username, :email, :password_hash)'
        );

        $statement->execute([
            'username'      => $username,
            'email'         => $email,
            'password_hash' => $passwordHash,            
        ]);
    }

    public function findByUsernameOrEmail(string $identifier): ?User
    {
        $statement = $this->getPdo()->prepare(
            'SELECT user_id, user_name, email, password_hash 
             FROM User_ 
             WHERE user_name = :identifier OR email = :identifier'
        );
        $statement->execute(['identifier' => $identifier]);
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

    public function createPasswordReset(int $userId, string $tokenHash, int $expiresAt): void
    {
        if !($this->idExists($userId)){
            throw new UserException::invalidUserId();
        }

        $deleteStmt = $this->getPdo()->prepare('DELETE FROM PasswordReset_ WHERE user_id = :user_id');
        $deleteStmt->execute(['user_id' => $userId]);

        $statement = $this->getPdo()->prepare(
            'INSERT INTO PasswordReset_ (user_id, token_hash, expires_at) 
            VALUES (:user_id, :token_hash, :expires_at)'
        $statement->execute([
            'user_id'    => $userId,
            'token_hash' => $tokenHash,
            'expires_at' => $expiresAt,
        ]);
        
    }

    public function findUserByResetToken(string $tokenHash): ?User
    {
        $statement = $this->getPdo()->prepare(
            'SELECT u.user_id, u.user_name, u.email, u.password_hash 
             FROM User_ u
             INNER JOIN PasswordReset_ pr ON u.user_id = pr.user_id
             WHERE pr.token_hash = :token_hash AND pr.expires_at > :current_time'
        );
        $statement->execute([
            'token_hash'   => $tokenHash,
            'current_time' => time(),
        ]);
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

    public function deletePasswordReset(string $tokenHash): bool
    {
        $statement = $this->getPdo()->prepare('DELETE FROM PasswordReset_ WHERE token_hash = :token_hash');
        return $statement->execute(['token_hash' => $tokenHash]);
    }

    public function updatePassword(int $userId, string $passwordHash): bool
    {

        if !($this->idExists($userId)){
            throw new UserException::invalidUserId();
        }

        $statement = $this->getPdo()->prepare(
            'UPDATE User_ 
             SET password_hash = :password_hash 
             WHERE user_id = :user_id'
        );

        return $statement->execute([
            'password_hash' => $passwordHash,
            'user_id'       => $userId,
        ]);
    }
}
