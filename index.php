<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/_assets/includes/exceptions/autoloader.php';

$action = $_GET['action'] ?? 'home';

switch ($action) {
    case 'login':
        (new \Auth\Controllers\Login\Login())->execute();
        break;

    case 'register':
        (new \Auth\Controllers\Register\Register())->execute();
        break;

    case 'logout':
        (new \Auth\Controllers\Logout\Logout())->execute();
        break;

    case 'home':
    default:
        if (isset($_SESSION['user'])) {
            ?>
            <!DOCTYPE html>
            <html lang="fr">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Accueil - SAE PHP</title>
                <style>
                    body {
                        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
                        background: linear-gradient(135deg, #f5f7fb 0%, #e5e9f2 100%);
                        min-height: 100vh;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        margin: 0;
                        padding: 20px;
                    }
                    .dashboard-card {
                        background: #ffffff;
                        padding: 40px;
                        border-radius: 12px;
                        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
                        max-width: 480px;
                        width: 100%;
                        text-align: center;
                    }
                    h1 {
                        color: #111827;
                        font-size: 24px;
                        margin-bottom: 12px;
                    }
                    .user-info {
                        background: #f9fafb;
                        border: 1px solid #e5e7eb;
                        border-radius: 8px;
                        padding: 16px;
                        margin: 20px 0;
                        text-align: left;
                        font-size: 15px;
                        color: #374151;
                    }
                    .user-info p {
                        margin: 6px 0;
                    }
                    .user-info strong {
                        color: #111827;
                    }
                    .btn-logout {
                        display: inline-block;
                        padding: 12px 24px;
                        background-color: #ef4444;
                        color: #ffffff;
                        text-decoration: none;
                        border-radius: 8px;
                        font-weight: 600;
                        transition: background-color 0.2s;
                    }
                    .btn-logout:hover {
                        background-color: #dc2626;
                    }
                </style>
            </head>
            <body>
                <div class="dashboard-card">
                    <h1>Bienvenue, <?= htmlspecialchars($_SESSION['user']['username']) ?> !</h1>
                    <div class="user-info">
                        <p><strong>Identifiant :</strong> <?= htmlspecialchars($_SESSION['user']['username']) ?></p>
                        <p><strong>Email :</strong> <?= htmlspecialchars($_SESSION['user']['email']) ?></p>
                        <p><strong>Statut :</strong> Connecté</p>
                    </div>
                    <a href="index.php?action=logout" class="btn-logout">Se déconnecter</a>
                </div>
            </body>
            </html>
            <?php
        } else {
            header('Location: index.php?action=login');
            exit;
        }
        break;
}
