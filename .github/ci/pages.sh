#!/usr/bin/env bash
# Lance le site avec le serveur PHP intégré, visite toutes les pages
# (déconnecté puis connecté) et vérifie qu'aucune ne plante.
# Les pages HTML sont gardées dans pages/ pour la validation W3C.
#
# Variables utilisées : DB_HOST DB_PORT DB_NAME DB_USER DB_PASS (base déjà créée)
set -uo pipefail

PORT="${PORT:-8000}"
B="http://127.0.0.1:$PORT/index.php"
OUT=pages
J=$(mktemp)
rm -rf "$OUT" && mkdir -p "$OUT"

php -S "127.0.0.1:$PORT" -t public > php-server.log 2>&1 &
SERVER=$!
trap 'kill $SERVER 2>/dev/null' EXIT
for _ in $(seq 1 20); do curl -s -o /dev/null "$B" && break; sleep 0.3; done

ERREURS=0

# page NOM URL CODE_ATTENDU [options curl...]
page() {
    local nom="$1" url="$2" attendu="$3"; shift 3
    local code
    code=$(curl -s -b "$J" -c "$J" -o "$OUT/$nom.html" -w '%{http_code}' "$@" "$B$url")
    if [ "$code" != "$attendu" ]; then
        echo "::error::$nom ($url) : code HTTP $code au lieu de $attendu"
        ERREURS=$((ERREURS + 1))
    elif grep -qiE 'Fatal error|Warning:|Notice:|Deprecated:|Uncaught|Vue introuvable|Erreur de connexion' "$OUT/$nom.html"; then
        echo "::error::$nom ($url) : erreur PHP dans la page"
        grep -oiE '(Fatal error|Warning|Notice|Deprecated|Uncaught)[^<]{0,200}' "$OUT/$nom.html" | head -3
        ERREURS=$((ERREURS + 1))
    else
        echo "OK  $code  $nom ($url)"
    fi
}

csrf() {
    curl -s -b "$J" -c "$J" "$B?action=$1" \
        | grep -o 'name="csrf_token" value="[^"]*"' | head -1 | sed 's/.*value="//;s/"$//'
}

# Compte de test créé directement en base (l'inscription vérifie le domaine
# de l'email par DNS, ce qu'on évite en CI) + quelques joueurs pour le classement
PSEUDO="ci_test"
MDP='Motdepasse-CI-2026!'
PSEUDO="$PSEUDO" MDP="$MDP" php -r '
$pdo = new PDO(sprintf("mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4", getenv("DB_HOST"), getenv("DB_PORT"), getenv("DB_NAME")), getenv("DB_USER"), getenv("DB_PASS"));
$add = $pdo->prepare("INSERT IGNORE INTO User_ (user_name, email, password_hash, nb_points, progression) VALUES (?, ?, ?, ?, ?)");
$add->execute([getenv("PSEUDO"), getenv("PSEUDO") . "@example.com", password_hash(getenv("MDP"), PASSWORD_DEFAULT), 50, 10]);
for ($i = 1; $i <= 7; $i++) { $add->execute(["joueur$i", "joueur$i@example.com", password_hash("x", PASSWORD_DEFAULT), $i * 10, $i]); }
' || { echo "::error::impossible de créer le compte de test en base"; exit 1; }

echo "== Pages publiques"
page accueil            ""                          200
page accueil-page2      "?page=2"                   200
page a-propos           "?action=a-propos"          200
page contact            "?action=contact"           200
page mentions-legales   "?action=mentions-legales"  200
page plan-site          "?action=plan-site"         200
page login              "?action=login"             200
page register           "?action=register"          200
page forgot-password    "?action=forgot_password"   200
page reset-password     "?action=reset_password&token=test" 200
page erreur-404         "?action=page-inexistante"  404

echo "== Formulaires"
page register-erreur "?action=register" 200 \
    --data-urlencode "csrf_token=$(csrf register)" \
    -d "username=a" -d "email=a@example.com" -d "password=a" -d "password_confirm=b"
grep -q '⚠' "$OUT/register-erreur.html" || { echo "::error::l'inscription invalide n'affiche pas d'erreur"; ERREURS=$((ERREURS + 1)); }
page contact-erreur "?action=contact" 200 \
    --data-urlencode "csrf_token=$(csrf contact)" -d "email=pasunmail" -d "objet=" -d "message="
page login-erreur "?action=login" 200 \
    --data-urlencode "csrf_token=$(csrf login)" -d "identifier=$PSEUDO" -d "password=mauvais"
page login-post "?action=login" 302 \
    --data-urlencode "csrf_token=$(csrf login)" -d "identifier=$PSEUDO" --data-urlencode "password=$MDP"

echo "== Pages connecté"
page accueil-connecte   ""                  200
page compte             "?action=account"   200
grep -q "$PSEUDO" "$OUT/compte.html" || { echo "::error::la connexion n'a pas marché (pseudo absent de Mon compte)"; ERREURS=$((ERREURS + 1)); }

echo "== Sécurité"
page post-sans-csrf "?action=contact" 403 -d "email=a@b.fr"

# pages de redirection (302) : pas de HTML à valider
rm -f "$OUT"/login-post.html

if grep -qiE 'Fatal error|Warning|Uncaught' php-server.log; then
    echo "::error::erreurs PHP dans le journal du serveur :"
    grep -iE 'Fatal error|Warning|Uncaught' php-server.log | head -10
    ERREURS=$((ERREURS + 1))
fi

echo
[ "$ERREURS" -eq 0 ] && echo "Toutes les pages fonctionnent." || echo "$ERREURS problème(s)."
exit "$ERREURS"
