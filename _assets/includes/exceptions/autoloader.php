<?php

spl_autoload_register(function (string $class): void {
    $class = ltrim($class, '\\');

    if (str_starts_with($class, 'Auth\\')) {
        $parts = explode('\\', $class);
        $type = strtolower($parts[1] ?? '');
        $name = strtolower($parts[2] ?? '');

        if ($type === 'model' || $type === 'models') {
            $file = __DIR__ . '/../../../modules/auth/models/' . $name . '.php';
            if (file_exists($file)) {
                require_once $file;
                return;
            }
        }

        if ($type === 'controllers' || $type === 'controller') {
            $snakeName = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $parts[2] ?? ''));
            $file1 = __DIR__ . '/../../../modules/auth/controllers/' . $name . '.php';
            $file2 = __DIR__ . '/../../../modules/auth/controllers/' . $snakeName . '.php';
            if (file_exists($file1)) {
                require_once $file1;
                return;
            }
            if (file_exists($file2)) {
                require_once $file2;
                return;
            }
        }
    }

    if (str_starts_with($class, 'Includes\\Database\\')) {
        $file = __DIR__ . '/database.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }

    if (str_starts_with($class, 'Utils\\')) {
        $parts = explode('\\', $class);
        $name = $parts[1] ?? '';
        $file = __DIR__ . '/../../utils/class/' . $name . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }

    if (str_starts_with($class, 'Blog\\')) {
        $parts = explode('\\', $class);
        $type = strtolower($parts[1] ?? '');
        $name = strtolower($parts[2] ?? '');

        if ($type === 'model' || $type === 'models') {
            $file = __DIR__ . '/../../../modules/blog/models/' . $name . '.php';
            if (file_exists($file)) {
                require_once $file;
                return;
            }
        }

        if ($type === 'controllers' || $type === 'controller') {
            $file = __DIR__ . '/../../../modules/blog/controllers/' . $name . '.php';
            if (file_exists($file)) {
                require_once $file;
                return;
            }
        }

        if ($type === 'views' || $type === 'view') {
            $file = __DIR__ . '/../../../modules/blog/views/' . $name . '.php';
            if (file_exists($file)) {
                require_once $file;
                return;
            }
        }
    }

    if (str_starts_with($class, 'SAE_PHP\\')) {
        $relative = substr($class, strlen('SAE_PHP\\'));
        $file = __DIR__ . '/../../../' . str_replace('\\', '/', $relative) . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }

    $topDirs = ['controllers\\', 'views\\', 'models\\', 'routes\\'];
    foreach ($topDirs as $dirPrefix) {
        if (str_starts_with($class, $dirPrefix)) {
            $file = __DIR__ . '/../../../' . str_replace('\\', '/', $class) . '.php';
            if (file_exists($file)) {
                require_once $file;
                return;
            }
        }
    }
});
