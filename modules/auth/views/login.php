<?php
$logoutSuccess = isset($_GET['logout']) && $_GET['logout'] === 'success';
$registeredSuccess = isset($_GET['registered']) && $_GET['registered'] === 'success';
$resetSuccess = isset($_GET['reset']) && $_GET['reset'] === 'success';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
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

        .login-container {
            background-color: #ffffff;
            width: 100%;
            max-width: 400px;
            padding: 36px 32px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        }

        .login-header {
            text-align: center;
            margin-bottom: 28px;
        }

        .login-header h1 {
            font-size: 26px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 8px;
        }

        .login-header p {
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

        .alert-success {
            background-color: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .alert-danger {
            background-color: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .form-group {
            margin-bottom: 20px;
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
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
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
            margin-top: 6px;
        }

        .btn-submit:hover {
            background-color: #1d4ed8;
        }

        .btn-submit:active {
            transform: scale(0.99);
        }

        .register-link {
            margin-top: 24px;
            text-align: center;
            font-size: 14px;
            color: #6b7280;
        }

        .register-link a {
            color: #2563eb;
            text-decoration: none;
            font-weight: 600;
        }

        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>Connexion</h1>
            <p>Veuillez renseigner vos identifiants</p>
        </div>

        <?php if ($logoutSuccess): ?>
            <div class="alert alert-success">
                Vous avez été déconnecté avec succès.
            </div>
        <?php endif; ?>

        <?php if ($registeredSuccess): ?>
            <div class="alert alert-success">
                Votre compte a été créé avec succès ! Vous pouvez maintenant vous connecter.
            </div>
        <?php endif; ?>

        <?php if ($resetSuccess): ?>
            <div class="alert alert-success">
                Votre mot de passe a été réinitialisé avec succès ! Vous pouvez maintenant vous connecter.
            </div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form action="index.php?action=login" method="post">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(\Utils\Csrf::generateToken(), ENT_QUOTES, 'UTF-8') ?>">
            <div class="form-group">
                <label for="login">Identifiant :</label>
                <input type="text" id="login" name="login" required autocomplete="username" placeholder="Votre identifiant" value="<?= htmlspecialchars($_POST['login'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" required autocomplete="current-password" placeholder="Votre mot de passe">
            </div>

            <div style="text-align: right; margin-top: -12px; margin-bottom: 16px;">
                <a href="index.php?action=forgot_password" style="font-size: 13px; color: #2563eb; text-decoration: none;">Mot de passe oublié ?</a>
            </div>

            <button type="submit" class="btn-submit">Se connecter</button>
        </form>

        <div class="register-link">
            Pas encore de compte ? <a href="index.php?action=register">S'inscrire</a>
        </div>
    </div>
</body>
</html>
