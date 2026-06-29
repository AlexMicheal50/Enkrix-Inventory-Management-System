<?php

declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH',  BASE_PATH . '/app');

// Load .env
$envFile = BASE_PATH . '/.env';
if (file_exists($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if (str_starts_with(trim($line), '#') || !str_contains($line, '=')) continue;
        [$key, $val] = explode('=', $line, 2);
        $_ENV[trim($key)] = trim($val);
        putenv(trim($key) . '=' . trim($val));
    }
}

// Autoloader
spl_autoload_register(function (string $class): void {
    $map = [
        'App\\Config\\'      => APP_PATH . '/config/',
        'App\\Controllers\\' => APP_PATH . '/controllers/',
        'App\\Models\\'      => APP_PATH . '/models/',
        'App\\Middleware\\'  => APP_PATH . '/middleware/',
    ];
    foreach ($map as $prefix => $dir) {
        if (str_starts_with($class, $prefix)) {
            $file = $dir . str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';
            if (file_exists($file)) require_once $file;
            return;
        }
    }
});

require_once APP_PATH . '/helpers/functions.php';

// Session
session_start([
    'cookie_httponly' => true,
    'cookie_samesite' => 'Lax',
    'use_strict_mode' => true,
]);

// Route
require_once APP_PATH . '/routes/web.php';
