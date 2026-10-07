<?php

namespace Auth\Model\User;

use PDO;
use SAE_PHP\models\Exceptions\UserException;

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
        return $this->progression;
    }
}

class UserRepository
{
    private const DUMMY_PASSWORD_HASH = '$2y$10$uQrnMSGl8VfjQOoYbYtY6.yQkCUc3m8Y5CTgcFAMdbqP2mXQ6vIq6';

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

    /** Nombre total d'utilisateurs (pour la pagination). */
    public function countAll(): int
    {
        return (int) $this->getPdo()->query('SELECT COUNT(*) FROM User_')->fetchColumn();
    }

    /**
     * Une page du classement, du meilleur score au plus faible.
     *
     * @return User[]
     */
    public function findRankingPage(int $limit, int $offset): array
    {
        $statement = $this->getPdo()->prepare(
            'SELECT user_id, user_name, email, nb_points, progression
             FROM User_
             ORDER BY nb_points DESC, progression DESC, user_name ASC
             LIMIT :limit OFFSET :offset'
        );
        // PARAM_INT obligatoire : sinon MySQL reçoit LIMIT '10' et refuse la requête
        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $statement->bindValue(':offset', $offset, PDO::PARAM_INT);
        $statement->execute();

        $users = [];
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $users[] = new User(
                (int) $row['user_id'],
                $row['user_name'],
                $row['email'],
                null,
                (int) $row['nb_points'],
                (float) $row['progression']
            );
        }

        return $users;
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
            (int) $row['nb_points'],
            (float) $row['progression']
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
            (int) $row['nb_points'],
            (float) $row['progression']
        );
    }

    public function findById(int $userId): ?User
    {
        $statement = $this->getPdo()->prepare(
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
            (int) $row['nb_points'],
            (float) $row['progression']
        );
    }

    public function findByUsernameOrEmail(string $identifier): ?User
    {
        $statement = $this->getPdo()->prepare(
            'SELECT *
             FROM User_ 
             WHERE user_name = :username OR email = :email'
        );
        $statement->execute([
            'username' => $identifier,
            'email'    => $identifier,
        ]);
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return new User(
            (int) $row['user_id'],
            $row['user_name'],
            $row['email'],
            $row['password_hash'],
            (int) $row['nb_points'],
            (float) $row['progression']
        );
    }

    public function findUserByResetToken(string $tokenHash): ?User
    {
        $statement = $this->getPdo()->prepare(
            'SELECT u.user_id, u.user_name, u.email, u.password_hash , u.nb_points, u.progression
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
            $row['password_hash'],
            (int) $row['nb_points'],
            (float) $row['progression']
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

    public function tokenExists(string $tokenHash): bool
    {
        return $this->findUserByResetToken($tokenHash) !== null;
    }

    public function createUser(string $username, string $email, string $password, string $passwordConfirm): void
    {

        if ($username === '' || $email === '' || $password === '' || $passwordConfirm === '') {
            throw UserException::emptyField();
        }

        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            throw UserException::invalidEmailFormat();
        }

        if (!$this->emailDomainExists($email)) {
            throw UserException::emailDomainNotFound();
        }

        if ($password !== $passwordConfirm) {
            throw UserException::passwordsDontMatch();
        }

        if (strlen($password) < 12) {
            throw UserException::tooFewCharacters();
        }

        if (strlen($password) > 72) {
            throw UserException::tooManyCharacters();
        }

        if (!preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password)) {
            throw UserException::mustContainLetter();
        }

        if (!preg_match('/[0-9]/', $password) || !preg_match('/[\W_]/', $password)) {
            throw UserException::mustContainSymbol();
        }

        if ($this->usernameExists($username)){
            throw UserException::usernameAlreadyExists();
        }

        if ($this->emailExists($email)){
            throw UserException::emailAlreadyExists();
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $statement = $this->getPdo()->prepare(
            'INSERT INTO User_ (user_name, email, password_hash) 
            VALUES (:username, :email, :password_hash)'
        );

        $statement->execute([
            'username'      => $username,
            'email'         => $email,
            'password_hash' => $passwordHash       
        ]);
    }

    public function login(string $identifier, string $password): User
    {
        if ($identifier === '' || $password === '') {
            throw UserException::emptyField();
        }

        $user = $this->findByUsernameOrEmail($identifier);
        $passwordHash = $user?->getPasswordHash() ?? self::DUMMY_PASSWORD_HASH;

        if (!password_verify($password, $passwordHash) || $user === null) {
            throw UserException::invalidPasswordOrEmail();
        }

        return $user;
    }

    private function emailDomainExists(string $email): bool
    {
        $domain = substr((string) strrchr($email, '@'), 1);

        return $domain !== '' && checkdnsrr($domain, 'MX');
    }

    public function createPasswordReset(int $userId, string $tokenHash, int $expiresAt): void
    {
        if (!($this->idExists($userId))){
            throw UserException::invalidUserId();
        }

        $deleteStmt = $this->getPdo()->prepare('DELETE FROM PasswordReset_ WHERE user_id = :user_id');
        $deleteStmt->execute(['user_id' => $userId]);

        $statement = $this->getPdo()->prepare(
            'INSERT INTO PasswordReset_ (user_id, token_hash, expires_at) 
            VALUES (:user_id, :token_hash, :expires_at)'
        );
        $statement->execute([
            'user_id'    => $userId,
            'token_hash' => $tokenHash,
            'expires_at' => $expiresAt
        ]);
        
    }

    public function deletePasswordReset(string $tokenHash): void
    {   
        if (!($this->tokenExists($tokenHash))){
            throw UserException::invalidToken();
        }
        $statement = $this->getPdo()->prepare('DELETE FROM PasswordReset_ WHERE token_hash = :token_hash');
        $statement->execute(['token_hash' => $tokenHash]);
    }

    public function updateUsername(int $userId, string $username): void
    {
        if ($username === '') {
            throw UserException::emptyField();
        }

        if (!($this->idExists($userId))) {
            throw UserException::invalidUserId();
        }

        if ($this->usernameExists($username)) {
            throw UserException::usernameAlreadyExists();
        }

        $statement = $this->getPdo()->prepare(
            'UPDATE User_
             SET user_name = :username
             WHERE user_id = :user_id'
        );

        $statement->execute([
            'username' => $username,
            'user_id'  => $userId,
        ]);
    }

    public function checkPassword(int $userId, string $password): void
    {
        $user = $this->findById($userId);

        if ($user === null) {
            throw UserException::invalidUserId();
        }

        if ($password === '' || !password_verify($password, $user->getPasswordHash() ?? '')) {
            throw UserException::wrongCurrentPassword();
        }
    }

    public function updateEmail(int $userId, string $email): void
    {
        if ($email === '') {
            throw UserException::emptyField();
        }

        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            throw UserException::invalidEmailFormat();
        }

        if (!$this->emailDomainExists($email)) {
            throw UserException::emailDomainNotFound();
        }

        if (!($this->idExists($userId))) {
            throw UserException::invalidUserId();
        }

        if ($this->emailExists($email)) {
            throw UserException::emailAlreadyExists();
        }

        $statement = $this->getPdo()->prepare(
            'UPDATE User_
             SET email = :email
             WHERE user_id = :user_id'
        );

        $statement->execute([
            'email'   => $email,
            'user_id' => $userId,
        ]);
    }

    public function deleteUser(int $userId): void
    {
        if (!($this->idExists($userId))) {
            throw UserException::invalidUserId();
        }

        $statement = $this->getPdo()->prepare('DELETE FROM User_ WHERE user_id = :user_id');
        $statement->execute(['user_id' => $userId]);
    }

    public function updatePassword(int $userId, string $password, string $passwordConfirm): void
    {
        if ($password === '' || $passwordConfirm === '') {
            throw UserException::emptyField();
        }

        if (!($this->idExists($userId))){
            throw UserException::invalidUserId();
        }

        if ($password !== $passwordConfirm) {
            throw UserException::passwordsDontMatch();
        }

        if (strlen($password) < 12) {
            throw UserException::tooFewCharacters();
        }

        if (strlen($password) > 72) {
            throw UserException::tooManyCharacters();
        }

        if (!preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password)) {
            throw UserException::mustContainLetter();
        }

        if (!preg_match('/[0-9]/', $password) || !preg_match('/[\W_]/', $password)) {
            throw UserException::mustContainSymbol();
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $statement = $this->getPdo()->prepare(
            'UPDATE User_ 
             SET password_hash = :password_hash 
             WHERE user_id = :user_id'
        );
        
        $statement->execute([
            'password_hash' => $passwordHash,
            'user_id'       => $userId,
        ]);
    }
}
