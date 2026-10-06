<?php

spl_autoload_register(function (string $class): void {
    $rootDir = dirname(__DIR__, 2);

    $classMap = [
        'Includes\\Database\\DatabaseConnection' => __DIR__ . '/database.php',
        'Auth\\Model\\User\\User'                => $rootDir . '/app/models/user.php',
        'Auth\\Model\\User\\UserRepository'      => $rootDir . '/app/models/user.php',
    ];

    $prefixes = [
        'sae_php\\controllers\\' => $rootDir . '/app/controller/',
        'sae_php\\views\\'       => $rootDir . '/app/views/',
        'sae_php\\models\\'      => $rootDir . '/app/models/',
        'auth\\controllers\\'    => $rootDir . '/app/controller/',
        'utils\\'                => $rootDir . '/app/utils/',
        'routes\\'               => $rootDir . '/app/routes/',
    ];

    $class = ltrim($class, '\\');

    if (isset($classMap[$class])) {
        require_once $classMap[$class];
        return;
    }

    foreach ($prefixes as $prefix => $dir) {
        if (!str_starts_with(strtolower($class), $prefix)) {
            continue;
        }

        $relativePath = str_replace('\\', '/', substr($class, strlen($prefix)));
        $className = basename($relativePath);
        $snakeName = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $className));

        $candidates = [
            $dir . $relativePath . '.php',
            $dir . $className . '.php',
            $dir . $snakeName . '.php',
        ];

        foreach ($candidates as $file) {
            if (file_exists($file)) {
                require_once $file;
                return;
            }
        }
    }
});
