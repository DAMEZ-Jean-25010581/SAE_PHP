<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - SAE PHP</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
            background: linear-gradient(135deg, #f5f7fb 0%, #e5e9f2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: #1f2937;
        }

        .register-container {
            background-color: #ffffff;
            width: 100%;
            max-width: 420px;
            padding: 36px 32px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        }

        .register-header {
            text-align: center;
            margin-bottom: 28px;
        }

        .register-header h1 {
            font-size: 26px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 8px;
        }

        .register-header p {
            font-size: 14px;
            color: #6b7280;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 14px;
            margin-bottom: 20px;
            line-height: 1.4;
        }

        .alert-danger {
            background-color: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: #374151;
            margin-bottom: 6px;
        }

        .form-group input {
            width: 100%;
            padding: 11px 14px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 15px;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            background-color: #f9fafb;
        }

        .form-group input:focus {
            background-color: #ffffff;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2);
        }

        .btn-submit {
            width: 100%;
            padding: 12px;
            background-color: #2563eb;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s, transform 0.1s;
            margin-top: 8px;
        }

        .btn-submit:hover {
            background-color: #1d4ed8;
        }

        .btn-submit:active {
            transform: scale(0.99);
        }

        .login-link {
            margin-top: 24px;
            text-align: center;
            font-size: 14px;
            color: #6b7280;
        }

        .login-link a {
            color: #2563eb;
            text-decoration: none;
            font-weight: 600;
        }

        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-header">
            <h1>Inscription</h1>
            <p>Créez votre compte pour vous connecter</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form action="index.php?action=register" method="post">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(\Utils\Csrf::generateToken(), ENT_QUOTES, 'UTF-8') ?>">
            <div class="form-group">
                <label for="login">Identifiant :</label>
                <input type="text" id="login" name="login" required minlength="3" maxlength="30" pattern="^[a-zA-Z0-9_\-\.]{3,30}$" autocomplete="username" placeholder="Lettres, chiffres, tirets, underscores" value="<?= htmlspecialchars($_POST['login'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="email">Adresse email :</label>
                <input type="email" id="email" name="email" required maxlength="100" autocomplete="email" placeholder="votre.email@exemple.fr" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" required minlength="12" maxlength="72" autocomplete="new-password" placeholder="12 caractères min. (Maj, min, chiffre, symbole)">
            </div>

            <div class="form-group">
                <label for="password_confirm">Confirmer le mot de passe :</label>
                <input type="password" id="password_confirm" name="password_confirm" required minlength="12" maxlength="72" autocomplete="new-password" placeholder="Répétez le mot de passe">
            </div>

            <button type="submit" class="btn-submit">S'inscrire</button>
        </form>

        <div class="login-link">
            Déjà inscrit ? <a href="index.php?action=login">Se connecter</a>
        </div>
    </div>
</body>
</html>
