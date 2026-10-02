<?php

spl_autoload_register(function (string $class): void {
    $class = ltrim($class, '\\');
    $rootDir = dirname(__DIR__, 3);

    if (str_starts_with($class, 'Auth\\')) {
        $parts = explode('\\', $class);
        $type = strtolower($parts[1] ?? '');
        $name = strtolower($parts[2] ?? '');
        $snakeName = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $parts[2] ?? ''));
        $className = end($parts);
        $snakeClass = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $className));

        if ($type === 'model' || $type === 'models') {
            $candidates = [
                $rootDir . '/app/models/user.php',
                $rootDir . '/app/models/' . $name . '.php',
                $rootDir . '/app/models/' . $className . '.php',
                $rootDir . '/app/models/' . $snakeClass . '.php',
                $rootDir . '/app/models/' . strtolower($className) . '.php'
            ];
            foreach ($candidates as $file) {
                if (file_exists($file)) {
                    require_once $file;
                    return;
                }
            }
        }

        if ($type === 'controllers' || $type === 'controller') {
            $candidates = [
                $rootDir . '/app/controller/' . $className . '.php',
                $rootDir . '/app/controller/' . $snakeClass . '.php',
                $rootDir . '/app/controller/' . strtolower($className) . '.php',
                $rootDir . '/app/controller/' . $name . '.php',
                $rootDir . '/app/controller/' . $snakeName . '.php',
                $rootDir . '/app/controllers/' . $className . '.php',
                $rootDir . '/app/controllers/' . $snakeClass . '.php',
                $rootDir . '/app/controllers/' . $name . '.php',
                $rootDir . '/app/controllers/' . $snakeName . '.php'
            ];
            foreach ($candidates as $file) {
                if (file_exists($file)) {
                    require_once $file;
                    return;
                }
            }
        }
    }

    if (str_starts_with($class, 'Includes\\Database\\') || $class === 'SAE_PHP\\includes\\DatabaseConnection') {
        $file = __DIR__ . '/database.php';
        if (file_exists($file)) {
            require_once $file;
            if ($class === 'SAE_PHP\\includes\\DatabaseConnection' && !class_exists('SAE_PHP\\includes\\DatabaseConnection', false)) {
                class_alias('Includes\\Database\\DatabaseConnection', 'SAE_PHP\\includes\\DatabaseConnection');
            }
            return;
        }
    }

    if (str_starts_with($class, 'Utils\\')) {
        $parts = explode('\\', $class);
        $name = end($parts);
        $candidates = [
            dirname(__DIR__, 1) . '/utils/class/' . $name . '.php',
            dirname(__DIR__, 1) . '/utils/class/' . strtolower($name) . '.php',
            $rootDir . '/app/utils/' . $name . '.php'
        ];
        foreach ($candidates as $file) {
            if (file_exists($file)) {
                require_once $file;
                return;
            }
        }
    }

    if (str_starts_with($class, 'SAE_PHP\\')) {
        $relative = substr($class, strlen('SAE_PHP\\'));
        $parts = explode('\\', $relative);
        $folder = strtolower($parts[0] ?? '');
        $name = $parts[1] ?? '';
        $className = end($parts);
        $snakeName = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $className));

        if ($folder === 'controllers' || $folder === 'controller') {
            $candidates = [
                $rootDir . '/app/controller/' . $name . '.php',
                $rootDir . '/app/controller/' . $className . '.php',
                $rootDir . '/app/controller/' . $snakeName . '.php',
                $rootDir . '/app/controllers/' . $name . '.php',
                $rootDir . '/app/controllers/' . $className . '.php',
                $rootDir . '/app/controllers/' . $snakeName . '.php'
            ];
            foreach ($candidates as $file) {
                if (file_exists($file)) {
                    require_once $file;
                    return;
                }
            }
        }

        if ($folder === 'views' || $folder === 'view') {
            $candidates = [
                $rootDir . '/app/views/' . $name . '.php',
                $rootDir . '/app/views/' . $className . '.php',
                $rootDir . '/app/views/' . $snakeName . '.php'
            ];
            foreach ($candidates as $file) {
                if (file_exists($file)) {
                    require_once $file;
                    return;
                }
            }
        }

        if ($folder === 'models' || $folder === 'model') {
            $candidates = [
                $rootDir . '/app/models/user.php',
                $rootDir . '/app/models/' . $name . '.php',
                $rootDir . '/app/models/' . $className . '.php',
                $rootDir . '/app/models/' . strtolower($name) . '.php',
                $rootDir . '/app/models/' . strtolower($className) . '.php'
            ];
            foreach ($candidates as $file) {
                if (file_exists($file)) {
                    require_once $file;
                    return;
                }
            }
        }

        if ($folder === 'routes' || $folder === 'route') {
            $candidates = [
                $rootDir . '/app/routes/' . implode('/', array_slice($parts, 1)) . '.php',
                $rootDir . '/app/routes/' . $className . '.php'
            ];
            foreach ($candidates as $file) {
                if (file_exists($file)) {
                    require_once $file;
                    return;
                }
            }
        }

        $directFile = $rootDir . '/app/' . str_replace('\\', '/', $relative) . '.php';
        if (file_exists($directFile)) {
            require_once $directFile;
            return;
        }
    }

    $topCandidates = [
        $rootDir . '/app/' . str_replace('\\', '/', $class) . '.php',
        $rootDir . '/' . str_replace('\\', '/', $class) . '.php'
    ];
    foreach ($topCandidates as $file) {
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});
