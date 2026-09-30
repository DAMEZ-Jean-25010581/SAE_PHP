<?php

header('X-Frame-Options: SAMEORIGIN');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: strict-origin-when-cross-origin');

require __DIR__ . '/_assets/includes/exceptions/autoloader.php';

use \routes\Router;

new Router();

