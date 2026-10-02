<?php

namespace SAE_PHP\models;

use Includes\Database\DatabaseConnection;
use PDO;
use PDOException;

class UserRepository
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? DatabaseConnection::getInstance()->getConnection();
    }

    public function createUser(string $username, string $email, string $passwordHash): bool
    {
        try {
            $idStmt = $this->pdo->query('SELECT COALESCE(MAX(user_id), 0) + 1 FROM User_');
            $nextId = (int) $idStmt->fetchColumn();

            $statement = $this->pdo->prepare(
                'INSERT INTO User_ (user_id, user_name, email, password_hash)
                 VALUES (:user_id, :username, :email, :password_hash)'
            );

            return $statement->execute([
                'user_id'       => $nextId,
                'username'      => $username,
                'email'         => $email,
                'password_hash' => $passwordHash,
            ]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function findById(int $userId): ?User
    {
        return $this->findOneBy('user_id = :user_id', ['user_id' => $userId]);
    }

    public function findByUsername(string $username): ?User
    {
        return $this->findOneBy('user_name = :username', ['username' => $username]);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->findOneBy('email = :email', ['email' => $email]);
    }

    public function findByUsernameOrEmail(string $identifier): ?User
    {
        return $this->findOneBy(
            'user_name = :username OR email = :email',
            ['username' => $identifier, 'email' => $identifier]
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

    public function exists(string $username, string $email): bool
    {
        $statement = $this->pdo->prepare(
            'SELECT COUNT(*) FROM User_ WHERE user_name = :username OR email = :email'
        );
        $statement->execute([
            'username' => $username,
            'email'    => $email,
        ]);

        return (int) $statement->fetchColumn() > 0;
    }

    public function countAll(): int
    {
        return (int) $this->pdo->query('SELECT COUNT(*) FROM User_')->fetchColumn();
    }

    public function updateUsername(int $userId, string $username): bool
    {
        return $this->updateColumn($userId, 'user_name', $username);
    }

    public function updateEmail(int $userId, string $email): bool
    {
        return $this->updateColumn($userId, 'email', $email);
    }

    public function updatePassword(int $userId, string $passwordHash): bool
    {
        return $this->updateColumn($userId, 'password_hash', $passwordHash);
    }

    public function delete(int $userId): bool
    {
        try {
            $this->pdo->beginTransaction();

            $resetStmt = $this->pdo->prepare('DELETE FROM PasswordReset_ WHERE user_id = :user_id');
            $resetStmt->execute(['user_id' => $userId]);

            $userStmt = $this->pdo->prepare('DELETE FROM User_ WHERE user_id = :user_id');
            $userStmt->execute(['user_id' => $userId]);

            $this->pdo->commit();
            return $userStmt->rowCount() > 0;
        } catch (PDOException $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            error_log($e->getMessage());
            return false;
        }
    }

    public function createPasswordReset(int $userId, string $tokenHash, int $expiresAt): bool
    {
        try {
            $deleteStmt = $this->pdo->prepare('DELETE FROM PasswordReset_ WHERE user_id = :user_id');
            $deleteStmt->execute(['user_id' => $userId]);

            $statement = $this->pdo->prepare(
                'INSERT INTO PasswordReset_ (user_id, token_hash, expires_at)
                 VALUES (:user_id, :token_hash, :expires_at)'
            );

            return $statement->execute([
                'user_id'    => $userId,
                'token_hash' => $tokenHash,
                'expires_at' => $expiresAt,
            ]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function findUserByResetToken(string $tokenHash): ?User
    {
        $statement = $this->pdo->prepare(
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

        return $row ? $this->hydrate($row) : null;
    }

    public function deletePasswordReset(string $tokenHash): bool
    {
        try {
            $statement = $this->pdo->prepare('DELETE FROM PasswordReset_ WHERE token_hash = :token_hash');
            return $statement->execute(['token_hash' => $tokenHash]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    private function findOneBy(string $where, array $params): ?User
    {
        $statement = $this->pdo->prepare(
            'SELECT user_id, user_name, email, password_hash FROM User_ WHERE ' . $where
        );
        $statement->execute($params);
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->hydrate($row) : null;
    }

    private function updateColumn(int $userId, string $column, string $value): bool
    {
        try {
            $statement = $this->pdo->prepare(
                'UPDATE User_ SET ' . $column . ' = :value WHERE user_id = :user_id'
            );

            return $statement->execute([
                'value'   => $value,
                'user_id' => $userId,
            ]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    private function hydrate(array $row): User
    {
        return new User(
            (int) $row['user_id'],
            $row['user_name'],
            $row['email'],
            $row['password_hash']
        );
    }
}
