<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Déconnexion - SAE PHP</title>
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

        .logout-container {
            background-color: #ffffff;
            width: 100%;
            max-width: 420px;
            padding: 40px 32px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            text-align: center;
        }

        .icon-circle {
            width: 64px;
            height: 64px;
            background-color: #ecfdf5;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px auto;
            color: #10b981;
            font-size: 32px;
        }

        h1 {
            font-size: 24px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 12px;
        }

        p {
            font-size: 15px;
            color: #4b5563;
            line-height: 1.5;
            margin-bottom: 28px;
        }

        .btn-reconnect {
            display: inline-block;
            width: 100%;
            padding: 12px;
            background-color: #2563eb;
            color: #ffffff;
            text-decoration: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            transition: background-color 0.2s, transform 0.1s;
        }

        .btn-reconnect:hover {
            background-color: #1d4ed8;
        }

        .btn-reconnect:active {
            transform: scale(0.99);
        }
    </style>
</head>
<body>
    <div class="logout-container">
        <div class="icon-circle">✓</div>
        <h1>Déconnexion réussie</h1>
        <p>Votre session a bien été fermée. Vous pouvez maintenant vous reconnecter ou fermer cette page en toute sécurité.</p>
        <a href="index.php?action=login" class="btn-reconnect">Se reconnecter</a>
    </div>
</body>
</html>
