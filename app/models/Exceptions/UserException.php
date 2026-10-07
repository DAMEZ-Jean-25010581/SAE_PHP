<?php

namespace SAE_PHP\models\Exceptions;

class UserException extends \RuntimeException {
    private const EMAIL_ALREADY_EXISTS = "Cet email est déjà utilisé.";
    private const EMAIL_ALREADY_EXISTS_CODE = 1001;

    public static function emailAlreadyExists(): self {
        return new self(self::EMAIL_ALREADY_EXISTS, self::EMAIL_ALREADY_EXISTS_CODE);
    }

    private const USERNAME_ALREADY_EXISTS = "Ce nom d'utilisateur est déjà utilisé.";
    private const USERNAME_ALREADY_EXISTS_CODE = 1002;

    public static function usernameAlreadyExists(): self {
        return new self(self::USERNAME_ALREADY_EXISTS, self::USERNAME_ALREADY_EXISTS_CODE);
    }

    private const INVALID_EMAIL_FORMAT = "L'adresse email n'est pas valide.";
    private const INVALID_EMAIL_FORMAT_CODE = 1003;
    public static function invalidEmailFormat(): self {
        return new self(self::INVALID_EMAIL_FORMAT, self::INVALID_EMAIL_FORMAT_CODE);
    }

    private const INVALID_USER_ID = "Id utilisateur invalide.";
    private const INVALID_USER_ID_CODE = 1004;
    public static function invalidUserId(): self {
        return new self(self::INVALID_USER_ID, self::INVALID_USER_ID_CODE);
    }

    private const INVALID_PASSWORD_OR_EMAIL = "Identifiant ou mot de passe invalide.";
    private const INVALID_PASSWORD_OR_EMAIL_CODE = 1005;
    public static function invalidPasswordOrEmail(): self {
        return new self(self::INVALID_PASSWORD_OR_EMAIL, self::INVALID_PASSWORD_OR_EMAIL_CODE);
    }

    private const PASSWORDS_DONT_MATCH = "Les mots de passe ne correspondent pas.";
    private const PASSWORDS_DONT_MATCH_CODE = 1006;
    public static function passwordsDontMatch(): self {
        return new self(self::PASSWORDS_DONT_MATCH, self::PASSWORDS_DONT_MATCH_CODE);
    }

    private const INVALID_TOKEN = "Token invalide ou expiré.";
    private const INVALID_TOKEN_CODE = 1007;
    public static function invalidToken(): self {
        return new self(self::INVALID_TOKEN, self::INVALID_TOKEN_CODE);
    }

    private const TOO_FEW_CHARACTERS = "Le mot de passe doit contenir au moins 12 caractères.";
    private const TOO_FEW_CHARACTERS_CODE = 1008;
    public static function tooFewCharacters():self {
        return new self(self::TOO_FEW_CHARACTERS, self::TOO_FEW_CHARACTERS_CODE);
    }

    private const TOO_MANY_CHARACTERS = "Le mot de passe doit contenir au maximum 72 caractères.";
    private const TOO_MANY_CHARACTERS_CODE = 1009;
    public static function tooManyCharacters():self {
        return new self(self::TOO_MANY_CHARACTERS, self::TOO_MANY_CHARACTERS_CODE);
    }

    private const MUST_CONTAIN_LETTER = "Le mot de passe doit contenir au moins une majuscule et une minuscule.";
    private const MUST_CONTAIN_LETTER_CODE = 1010;
    public static function mustContainLetter():self {
        return new self(self::MUST_CONTAIN_LETTER, self::MUST_CONTAIN_LETTER_CODE);
    }

    private const MUST_CONTAIN_SYMBOL = "Le mot de passe doit contenir au moins un chiffre et un caractère spécial.";
    private const MUST_CONTAIN_SYMBOL_CODE = 1011;
    public static function mustContainSymbol():self {
        return new self(self::MUST_CONTAIN_SYMBOL, self::MUST_CONTAIN_SYMBOL_CODE);
    }

    private const EMPTY_FIELD = "Veuillez renseigner tous les champs.";
    private const EMPTY_FIELD_CODE = 1012;
    public static function emptyField():self {
        return new self(self::EMPTY_FIELD, self::EMPTY_FIELD_CODE);
    }

    private const WRONG_CURRENT_PASSWORD = "Le mot de passe actuel est incorrect.";
    private const WRONG_CURRENT_PASSWORD_CODE = 1013;
    public static function wrongCurrentPassword():self {
        return new self(self::WRONG_CURRENT_PASSWORD, self::WRONG_CURRENT_PASSWORD_CODE);
    }

    private const EMAIL_DOMAIN_NOT_FOUND = "Cette adresse email n'existe pas.";
    private const EMAIL_DOMAIN_NOT_FOUND_CODE = 1014;
    public static function emailDomainNotFound():self {
        return new self(self::EMAIL_DOMAIN_NOT_FOUND, self::EMAIL_DOMAIN_NOT_FOUND_CODE);
    }

}