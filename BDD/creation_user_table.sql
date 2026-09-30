-- V1.6.2

PROMPT "Création de la base de données User";

-- **************************************************************************** Définition des données

PROMPT "Suppression de toutes les éventuelles vues";

BEGIN
   FOR v IN (SELECT view_name FROM user_views) 
   LOOP
      EXECUTE IMMEDIATE 'DROP VIEW "' || v.view_name || '"';
   END LOOP;
END;
/

PROMPT "Définition des données";

DROP TABLE IF EXISTS User_ CASCADE CONSTRAINTS PURGE;
DROP TABLE IF EXISTS Session_ CASCADE CONSTRAINTS PURGE;

CREATE TABLE User_(
   user_id INT,
   user_name VARCHAR(50) NOT NULL,
   email VARCHAR(50) NOT NULL,
   password_hash VARCHAR(255) NOT NULL,
   PRIMARY KEY(user_id),
   UNIQUE(user_name),
   UNIQUE(email)
);

CREATE TABLE Session_(
   session_id COUNTER,
   token CHAR(18) NOT NULL,
   expires_at DATETIME,
   user_id INT NOT NULL,
   PRIMARY KEY(session_id),
   UNIQUE(token),
   FOREIGN KEY(user_id) REFERENCES User_(user_id)
);

-- Insertion de l'utilisateur admin par défaut
INSERT INTO User_ (user_id, user_name, email, password_hash)
VALUES (1, 'wanis', 'wanis@univ-amu.fr', '$2y$10$OfMA8xv7SzCLO6E1FVB8u.SgKJGRVotmabJHYGLwZ8/Rigc4Vm5ba');

-- Contraintes.





