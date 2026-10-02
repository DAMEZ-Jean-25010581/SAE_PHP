<?php
namespace SAE_PHP\models;

use SAE_PHP\includes\DatabaseConnection;

class User {

    public static function idExists(int $id): bool {
        return self::findById($id) !== null;
    }

    public static function findById(int $userId): ?array {
        $pdo = DatabaseConnection::getInstance();
        $stmt = $pdo->prepare('SELECT * FROM User_ WHERE user_id = ?');
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    public static function usernameExists(string $username): bool {
            return self::findByUsername($username) !== null;
    }

    public static function findByUsername(string $username): ?array {
        $pdo = DatabaseConnection::getInstance();
        $stmt = $pdo->prepare('SELECT * FROM User_ WHERE user_name = ?');
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    public static function emailExists(string $email): bool {
        return self::findByEmail($email) !== null;
    }

    public static function findByEmail(string $email): ?array {
        $pdo = DatabaseConnection::getInstance();
        $stmt = $pdo->prepare('SELECT * FROM users where email=?');
        $stmt -> execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    public static function create(string $email, string $password, string $passwordConfirmation): ?string {
        $pdo = DatabaseConnection::getInstance();
        $stmt = $pdo->prepare('INSERT INTO User_ (email, password_hash) VALUES(?,?)');

        if ($email === '' || $password === '' || $passwordConfirmation === '') {
            return "Veuillez renseigner tous les champs.";
        }

        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            return "L'adresse email n'est pas valide.";
        }

        if (self::emailExists($email)) {
            return "Cet email est déjà utilisé.";
        }

        if ($password !== $passwordConfirmation) {
            return "Les mots de passe ne correspondent pas.";
        }

        if (strlen($password) < 8) {
            return "Le mot de passe doit contenir au moins 8 caractères.";
        } 

        if (!preg_match('/[0-9]/', $password)) {
            return "Le mot de passe doit contenir au moins un chiffre.";
        }
        
        if (!preg_match('/[A-Z]/', $password)) {
            return "Le mot de passe doit contenir au moins une majuscule.";
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt -> execute([$email, $hashedPassword]);
        return null;
    }

   public static function authenticate(string $email, string $password): ?string {

	if ($email === '' || $password === ''){
		return "Veuillez renseigner tous les champs.";
	}
	
	if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            return "L'adresse email n'est pas valide.";
        }

	$user = self::findByEmail($email);

	if ($user === null){
		return "Utilisateur introuvable";
	}

	if (!(password_verify($password, $user['password_hash']))){
		return "Mot de passe ou email invalide.";
	}

	return null;
    }

    public function updateUsername(int $id, string $username): ?string {
        $pdo = DatabaseConnection::getInstance();
        $stmt = $pdo->prepare('UPDATE User_ SET user_name=? WHERE user_id=?');

        if (!(self::findById($id))){
            return "Id utilisateur invalide.";
        }

        if (self::usernameExists($username)){
            return "Ce nom d'utilisateur est déjà utilisé.";
        }

        $stmt->execute([$username,$id]);
        return null;
    }

    public function updateEmail(int $id, string $email): ?string {
        $pdo = DatabaseConnection::getInstance();
        $stmt = $pdo->prepare('UPDATE User_ SET email=? WHERE user_id=?');

        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                return "L'adresse email n'est pas valide.";
            }

        if (!(self::findById($id))){
            return "Id utilisateur invalide.";
        }
        
        if (self::emailExists($email)) {
                return "Cet email est déjà utilisé.";
        }

        if (self::emailExists($email)){
            return "Cet email est déjà utilisé.";
        }

        $stmt->execute([$email,$id]);
        return null;
    }

    public function updatePassword(int $id, string $password, string $passwordConfirmation): ?string {
        $pdo = DatabaseConnection::getInstance();
        $stmt = $pdo->prepare('UPDATE User_ SET password_hash=? WHERE user_id=?');

        if (!(self::findById($id))){
            return "Id utilisateur invalide.";
        }

        if ($password === '' || $passwordConfirmation === '') {
                return "Veuillez renseigner tous les champs.";
            }

            if ($password !== $passwordConfirmation) {
                return "Les mots de passe ne correspondent pas.";
            }

            if (strlen($password) < 8) {
                return "Le mot de passe doit contenir au moins 8 caractères.";
            } 

            if (!preg_match('/[0-9]/', $password)) {
                return "Le mot de passe doit contenir au moins un chiffre.";
            }
            
            if (!preg_match('/[A-Z]/', $password)) {
                return "Le mot de passe doit contenir au moins une majuscule.";
            }
        
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt -> execute([$hashedPassword, $id]);
            return null;
    }

    public function delete(int $id): ?string {
        $pdo = DatabaseConnection::getInstance();
        $stmt = $pdo->prepare('DELETE FROM User_ WHERE user_id=?');

        if (!(self::findById($id))){
                return "Id utilisateur invalide.";
        }
        $stmt->execute([$id]);
        return null;
    }

    public static function countAll(): int {
	$pdo = DatabaseConnection::getInstance();
	$stmt = $pdo->prepare('SELECT COUNT(*) FROM User_');
    $stmt->execute();
    return (int) $stmt->fetchColumn();
    }
    
}

?>