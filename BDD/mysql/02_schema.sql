-- =====================================================================
-- CyberLab - Schéma MySQL / MariaDB
-- Exécuté automatiquement par Docker au 1er démarrage,
-- et à importer tel quel dans la base alwaysdata.
-- Attention : sous Linux (alwaysdata), MySQL respecte la casse des noms
-- de tables -> toujours écrire les noms de tables avec le suffixe _.
-- =====================================================================

SET NAMES utf8mb4;

-- Ordre : enfants d'abord, parents ensuite
DROP TABLE IF EXISTS UserExamAnswer_;
DROP TABLE IF EXISTS UserTestChoice_;
DROP TABLE IF EXISTS UserExamResult_;
DROP TABLE IF EXISTS UserTestResult_;
DROP TABLE IF EXISTS UserCourseProgress_;
DROP TABLE IF EXISTS TestResponse_;
DROP TABLE IF EXISTS TestQuestion_;
DROP TABLE IF EXISTS Test_;
DROP TABLE IF EXISTS Course_;
DROP TABLE IF EXISTS Level_;
DROP TABLE IF EXISTS ExamQuestion_;
DROP TABLE IF EXISTS Synthesis_;
DROP TABLE IF EXISTS Exam_;
DROP TABLE IF EXISTS Module_;
DROP TABLE IF EXISTS PasswordReset_;
DROP TABLE IF EXISTS Session_;
DROP TABLE IF EXISTS ContactMessage_;
DROP TABLE IF EXISTS User_;

CREATE TABLE User_ (
    user_id       INT          NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_name     VARCHAR(50)  NOT NULL UNIQUE,
    email         VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    total_points  INT          NOT NULL DEFAULT 0
        CHECK (total_points >= 0),
    progress      INT          NOT NULL DEFAULT 0  -- pourcentage 0 à 100
        CHECK (progress BETWEEN 0 AND 100),
    CHECK (CHAR_LENGTH(TRIM(user_name)) > 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE PasswordReset_ (
    reset_id   INT          NOT NULL AUTO_INCREMENT PRIMARY KEY,
    token_hash VARCHAR(255) NOT NULL UNIQUE,       -- hash du token, jamais le token en clair
    expire_at  DATETIME     NOT NULL,
    user_id    INT          NOT NULL,
    FOREIGN KEY (user_id) REFERENCES User_(user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE Module_ (
    module_id          INT          NOT NULL AUTO_INCREMENT PRIMARY KEY,
    module_name        VARCHAR(255) NOT NULL UNIQUE,
    module_description TEXT         NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE Exam_ (
    exam_id   INT          NOT NULL AUTO_INCREMENT PRIMARY KEY,
    exam_name VARCHAR(255) NOT NULL,
    module_id INT          NOT NULL UNIQUE,        -- un seul examen par module
    FOREIGN KEY (module_id) REFERENCES Module_(module_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE Synthesis_ (
    synthesis_id      INT          NOT NULL AUTO_INCREMENT PRIMARY KEY,
    synthesis_name    VARCHAR(255) NOT NULL,
    synthesis_content MEDIUMTEXT   NOT NULL,       -- contenu HTML
    module_id         INT          NOT NULL UNIQUE, -- une unique synthese par module
    FOREIGN KEY (module_id) REFERENCES Module_(module_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE ExamQuestion_ (
    question_id      INT  NOT NULL AUTO_INCREMENT PRIMARY KEY,
    question_content TEXT NOT NULL,
    example_content  TEXT NULL,
    example_code     TEXT NULL,
    example_language ENUM('html','css','javascript','php','sql','python','bash','json') NULL,
    expected_answer  TEXT NOT NULL,                -- réponse attendue (texte libre)
    points           INT  NOT NULL DEFAULT 1
        CHECK (points > 0),
    exam_id          INT  NOT NULL,
    CHECK (example_language IS NULL OR example_language IS NOT NULL), -- permet de vérifier qu'un code exemple est bien associé à un langage, et qu'un langage est bien associé à un code exemple
    FOREIGN KEY (exam_id) REFERENCES Exam_(exam_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE Level_ (
    level_id    INT          NOT NULL AUTO_INCREMENT PRIMARY KEY,
    level_name  VARCHAR(255) NOT NULL,
    level_index INT          NOT NULL              -- ordre d'affichage dans le module (pour une bonne hierarchie)
        CHECK (level_index >= 1),
    module_id   INT          NOT NULL,
    UNIQUE (module_id, level_index),
    FOREIGN KEY (module_id) REFERENCES Module_(module_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE Course_ (
    course_id       INT          NOT NULL AUTO_INCREMENT PRIMARY KEY,
    course_name     VARCHAR(255) NOT NULL,
    course_content  MEDIUMTEXT   NOT NULL,         -- contenu HTML
    example_content MEDIUMTEXT   NOT NULL,         -- contenu HTML
    example_code    TEXT         NULL,             -- code source exemple (HTML, JS, PHP, etc.)
    example_language ENUM('html','css','javascript','php','sql','python','bash','json') NULL,
    course_index    INT          NOT NULL          -- ordre d'affichage dans le niveau (pour une bonne hierarchie)
        CHECK (course_index >= 1),
    level_id        INT          NOT NULL,
    UNIQUE (level_id, course_index),
    CHECK (example_language IS NULL OR example_language IS NOT NULL), -- permet de vérifier qu'un code exemple est bien associé à un langage, et qu'un langage est bien associé à un code exemple
    FOREIGN KEY (level_id) REFERENCES Level_(level_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE Test_ (
    test_id   INT          NOT NULL AUTO_INCREMENT PRIMARY KEY,
    test_name VARCHAR(255) NOT NULL,
    level_id  INT          NOT NULL UNIQUE,        -- un seul test par niveau
    FOREIGN KEY (level_id) REFERENCES Level_(level_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE TestQuestion_ (
    question_id      INT  NOT NULL AUTO_INCREMENT PRIMARY KEY,
    question_content TEXT NOT NULL,
    example_content  TEXT NULL,
    example_code     TEXT NULL,
    example_language ENUM('html','css','javascript','php','sql','python','bash','json') NULL,
    points           INT  NOT NULL DEFAULT 1
        CHECK (points > 0),
    test_id          INT  NOT NULL,
    CHECK (example_language IS NULL OR example_language IS NOT NULL), -- permet de vérifier qu'un code exemple est bien associé à un langage, et qu'un langage est bien associé à un code exemple
    FOREIGN KEY (test_id) REFERENCES Test_(test_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE TestResponse_ (
    response_id      INT     NOT NULL AUTO_INCREMENT PRIMARY KEY,
    response_content TEXT    NOT NULL,
    is_valid         BOOLEAN NOT NULL DEFAULT FALSE,
    question_id      INT     NOT NULL,
    FOREIGN KEY (question_id) REFERENCES TestQuestion_(question_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE UserCourseProgress_ (
    user_id      INT     NOT NULL,
    course_id    INT     NOT NULL,
    is_completed BOOLEAN NOT NULL DEFAULT FALSE,
    PRIMARY KEY (user_id, course_id),
    FOREIGN KEY (user_id)   REFERENCES User_(user_id)     ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES Course_(course_id)  ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE UserTestResult_ (
    user_id      INT     NOT NULL,
    test_id      INT     NOT NULL,
    is_completed BOOLEAN NOT NULL DEFAULT FALSE,   -- calculé par un trigger il faut min 75% (valeur exacte à determiner avec les autres membres du groupe) pour réussir le test
    grade        INT     NULL
        CHECK (grade IS NULL OR grade BETWEEN 0 AND 100),
    PRIMARY KEY (user_id, test_id),
    FOREIGN KEY (user_id) REFERENCES User_(user_id) ON DELETE CASCADE,
    FOREIGN KEY (test_id) REFERENCES Test_(test_id)  ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE UserExamResult_ (
    user_id      INT     NOT NULL,
    exam_id      INT     NOT NULL,
    is_completed BOOLEAN NOT NULL DEFAULT FALSE,   -- TRUE = module complété
    grade        INT     NULL
        CHECK (grade IS NULL OR (grade BETWEEN 0 AND 100)), -- NULL = pas encore noté, sinon note entre 0 et 100
    PRIMARY KEY (user_id, exam_id),
    FOREIGN KEY (user_id) REFERENCES User_(user_id) ON DELETE CASCADE,
    FOREIGN KEY (exam_id) REFERENCES Exam_(exam_id)  ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE UserTestChoice_ (
    user_id     INT NOT NULL,
    response_id INT NOT NULL,
    PRIMARY KEY (user_id, response_id),
    FOREIGN KEY (user_id)     REFERENCES User_(user_id)            ON DELETE CASCADE,
    FOREIGN KEY (response_id) REFERENCES TestResponse_(response_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE UserExamAnswer_ (
    user_id     INT     NOT NULL,
    question_id INT     NOT NULL,
    user_answer TEXT    NOT NULL,
    is_correct  BOOLEAN NOT NULL DEFAULT FALSE,    -- verdict figé par le backend (le backend compare la réponse de l'utilisateur avec la réponse attendue)
    PRIMARY KEY (user_id, question_id),
    FOREIGN KEY (user_id)     REFERENCES User_(user_id)            ON DELETE CASCADE,
    FOREIGN KEY (question_id) REFERENCES ExamQuestion_(question_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- =====================================================================
-- Triggers
-- =====================================================================

DROP TRIGGER IF EXISTS trg_before_insert_user_test_result;
DROP TRIGGER IF EXISTS trg_before_update_user_test_result;
DROP TRIGGER IF EXISTS trg_before_insert_user_exam_result;
DROP TRIGGER IF EXISTS trg_before_update_user_exam_result;


DELIMITER $$

CREATE TRIGGER trg_before_insert_user_test_result
BEFORE INSERT ON UserTestResult_
FOR EACH ROW
BEGIN
    DECLARE passing_grade INT;
    SET passing_grade = 80; -- seuil de réussite pour le test à modifier si besoin
    IF (NEW.grade IS NOT NULL AND NEW.grade >= passing_grade) THEN
        SET NEW.is_completed = TRUE;
    ELSE
        SET NEW.is_completed = FALSE;
    END IF;
END$$

CREATE TRIGGER trg_before_update_user_test_result
BEFORE UPDATE ON UserTestResult_
FOR EACH ROW
BEGIN
    DECLARE passing_grade INT;
    SET passing_grade = 80;
    IF (NEW.grade IS NOT NULL AND NEW.grade >= passing_grade) THEN
        SET NEW.is_completed = TRUE;
    ELSE
        SET NEW.is_completed = FALSE;
    END IF;
END$$

CREATE TRIGGER trg_before_update_user_exam_result
BEFORE UPDATE ON UserExamResult_
FOR EACH ROW
BEGIN
    DECLARE passing_grade INT;
    SET passing_grade = 75; -- seuil de réussite pour l'examen à modifier si besoin
    IF (NEW.grade IS NOT NULL AND NEW.grade >= passing_grade) THEN
        SET NEW.is_completed = TRUE;
    ELSE
        SET NEW.is_completed = FALSE;
    END IF;
END$$

CREATE TRIGGER trg_before_insert_user_exam_result
BEFORE INSERT ON UserExamResult_
FOR EACH ROW
BEGIN
    DECLARE passing_grade INT;
    SET passing_grade = 75;
    IF (NEW.grade IS NOT NULL AND NEW.grade >= passing_grade) THEN
        SET NEW.is_completed = TRUE;
    ELSE
        SET NEW.is_completed = FALSE;
    END IF;
END$$


DELIMITER ;