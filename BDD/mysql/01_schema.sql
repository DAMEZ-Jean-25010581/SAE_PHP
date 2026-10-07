-- =====================================================================
-- CyberLab - Schéma MySQL / MariaDB
-- Exécuté automatiquement par Docker au 1er démarrage,
-- et à importer tel quel dans la base alwaysdata.
-- Attention : sous Linux (alwaysdata), MySQL respecte la casse des noms
-- de tables -> toujours écrire User_ et PasswordReset_ comme ici.
-- =====================================================================

SET NAMES utf8mb4;

DROP TABLE IF EXISTS PasswordReset_;
DROP TABLE IF EXISTS Session_;
DROP TABLE IF EXISTS User_;
DROP TABLE IF EXISTS ContactMessage_;

CREATE TABLE User_ (
    user_id       INT          NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_name     VARCHAR(50)  NOT NULL UNIQUE,
    email         VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,           -- bcrypt = 60 car., prévoir plus
    nb_points     INT          NOT NULL DEFAULT 0,
    progression   DECIMAL(5,2) NOT NULL DEFAULT 0  -- pourcentage 0.00 à 100.00
        CHECK (progression BETWEEN 0 AND 100)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE Session_ (
    session_id INT         NOT NULL AUTO_INCREMENT PRIMARY KEY,
    token      VARCHAR(64) NOT NULL UNIQUE,
    expires_at DATETIME    NULL,
    user_id    INT         NOT NULL,
    FOREIGN KEY (user_id) REFERENCES User_(user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE PasswordReset_ (
    reset_id   INT         NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_id    INT         NOT NULL,
    token_hash VARCHAR(64) NOT NULL UNIQUE,
    expires_at BIGINT      NOT NULL,               -- timestamp Unix (time() en PHP)
    FOREIGN KEY (user_id) REFERENCES User_(user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE ContactMessage_ (
    contact_id INT         NOT NULL AUTO_INCREMENT PRIMARY KEY,
    email      VARCHAR(100) NOT NULL,
    objet      VARCHAR(100) NOT NULL,
    message    TEXT        NOT NULL,
    created_at TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;