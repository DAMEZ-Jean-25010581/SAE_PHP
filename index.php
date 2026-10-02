<?php

header('X-Frame-Options: SAMEORIGIN');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: strict-origin-when-cross-origin');

require_once __DIR__ . '/_assets/includes/exceptions/autoloader.php';

if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');

    $isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['SERVER_PORT']) && (int)$_SERVER['SERVER_PORT'] === 443);

    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'domain'   => '',
        'secure'   => $isSecure,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);

    session_start();
}

$action = $_GET['action'] ?? null;
if (!$action) {
    $requestUri = $_SERVER['REQUEST_URI'] ?? '';
    $path = trim(parse_url($requestUri, PHP_URL_PATH) ?? '', '/');
    $segments = explode('/', $path);
    $lastSegment = end($segments);
    if (!empty($lastSegment) && $lastSegment !== 'index.php') {
        $action = $lastSegment;
    } else {
        $action = 'home';
    }
}

switch ($action) {
    case 'login':
    case 'authentification':
        (new \Auth\Controllers\Login\Login())->execute();
        break;

    case 'register':
    case 'inscription':
        (new \Auth\Controllers\Register\Register())->execute();
        break;

    case 'logout':
    case 'deconnexion':
        (new \Auth\Controllers\Logout\Logout())->execute();
        break;

    case 'forgot_password':
        (new \Auth\Controllers\ForgotPassword\ForgotPassword())->execute();
        break;

    case 'reset_password':
        (new \Auth\Controllers\ResetPassword\ResetPassword())->execute();
        break;

    case 'a-propos':
        (new \SAE_PHP\controllers\APropos())->execute();
        break;

    case 'contact':
        (new \SAE_PHP\controllers\Contact())->execute();
        break;

    case 'mentions-legales':
        (new \SAE_PHP\controllers\MentionsLegales())->execute();
        break;

    case 'plan-site':
        (new \SAE_PHP\controllers\PlanSite())->execute();
        break;

    case 'home':
    default:
        (new \SAE_PHP\controllers\Homepage())->execute();
        break;
}
