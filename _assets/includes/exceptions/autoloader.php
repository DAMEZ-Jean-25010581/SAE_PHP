<?php

$root = __DIR__ . '/../../../';

spl_autoload_register(function ($class) use ($root) {
    $prefixes = [
    'controllers\\' => $root . '/controllers/',
    'models\\'      => $root . '/models/',
    'routes\\'      => $root . '/routes/',
    'utils\\'       => $root . '/utils/',
    ];

    foreach ($prefixes as $prefix => $baseDir) {
        if (strncmp($class, $prefix, strlen($prefix)) === 0) {
            $relative = substr($class, strlen($prefix));
            $file = $baseDir . str_replace('\\', '/', $relative) . '.php';
            if (is_file($file)) {
                require $file;
                return;
            }
        }
    }
});