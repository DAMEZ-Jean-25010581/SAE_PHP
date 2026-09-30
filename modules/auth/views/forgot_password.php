<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié - SAE PHP</title>
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

        .forgot-container {
            background-color: #ffffff;
            width: 100%;
            max-width: 420px;
            padding: 36px 32px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        }

        .forgot-header {
            text-align: center;
            margin-bottom: 28px;
        }

        .forgot-header h1 {
            font-size: 24px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 8px;
        }

        .forgot-header p {
            font-size: 14px;
            color: #6b7280;
            line-height: 1.5;
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

        .alert-info {
            background-color: #eff6ff;
            color: #1e40af;
            border: 1px solid #bfdbfe;
            word-break: break-all;
        }

        .alert-info a {
            color: #1d4ed8;
            font-weight: 600;
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
        }

        .btn-submit:hover {
            background-color: #1d4ed8;
        }

        .btn-submit:active {
            transform: scale(0.99);
        }

        .back-link {
            margin-top: 24px;
            text-align: center;
            font-size: 14px;
            color: #6b7280;
        }

        .back-link a {
            color: #2563eb;
            text-decoration: none;
            font-weight: 600;
        }

        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="forgot-container">
        <div class="forgot-header">
            <h1>Mot de passe oublié</h1>
            <p>Saisissez votre identifiant ou email pour recevoir un lien de réinitialisation.</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($successMessage)): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($successMessage, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($resetLink)): ?>
            <div class="alert alert-info">
                Lien de test : <a href="<?= htmlspecialchars($resetLink, ENT_QUOTES, 'UTF-8') ?>">Réinitialiser mon mot de passe</a>
            </div>
        <?php endif; ?>

        <form action="index.php?action=forgot_password" method="post">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(\Utils\Csrf::generateToken(), ENT_QUOTES, 'UTF-8') ?>">
            <div class="form-group">
                <label for="identifier">Identifiant ou Email :</label>
                <input type="text" id="identifier" name="identifier" required autocomplete="username" placeholder="Votre nom d'utilisateur ou email" value="<?= htmlspecialchars($_POST['identifier'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
            </div>

            <button type="submit" class="btn-submit">Envoyer le lien</button>
        </form>

        <div class="back-link">
            <a href="index.php?action=login">Retour à la connexion</a>
        </div>
    </div>
</body>
</html>
