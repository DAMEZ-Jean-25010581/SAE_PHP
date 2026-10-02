# SAE_PHP – CyberLab

## Lancer le projet en local (Docker)

Prérequis : Docker (Docker Desktop, ou Colima : `colima start`).

```bash
docker compose up -d --build
```

| Service | Adresse |
|---|---|
| Site | http://localhost:8080 |
| Adminer (voir la base) | http://localhost:8081 — Système **MySQL**, serveur `db`, utilisateur / mot de passe / base : `cyberlab` |

Au premier démarrage, MariaDB exécute les scripts de `BDD/mysql/` (ordre alphabétique).
Après une modification de ces scripts, repartir d'une base vide :

```bash
docker compose down -v && docker compose up -d
```

Arrêter : `docker compose down`.

## Base de données sur alwaysdata

1. Admin alwaysdata → **Bases de données → MySQL → Ajouter une base** (ex. `cyberlab_data`) et un utilisateur MySQL avec les droits dessus.
2. Importer le schéma depuis le conteneur (pas besoin d'installer `mysql` sur le Mac) :

   ```bash
   read -s "DBPW?Mot de passe MySQL alwaysdata : "; echo
   for f in BDD/mysql/01_schema.sql BDD/mysql/02_data.sql; do
     docker compose exec -T -e MYSQL_PWD="$DBPW" db mariadb -h mysql-cyberlab.alwaysdata.net -u cyberlab cyberlab_data < $f
   done
   ```

3. Sur le serveur, copier `_assets/config/config.local.php.example` en `_assets/config/config.local.php` et y mettre les vrais identifiants. Ce fichier n'est **jamais** poussé sur GitHub.

> Sous Linux (alwaysdata), MySQL respecte la casse des noms de tables : écrire `User_` et `PasswordReset_` exactement comme dans `BDD/mysql/01_schema.sql`.

## Connexion à la base dans le code

`Includes\Database\DatabaseConnection` (`_assets/includes/exceptions/database.php`) lit :
1. `_assets/config/config.local.php` s'il existe (alwaysdata) ;
2. sinon les variables d'environnement `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASS` (Docker).
