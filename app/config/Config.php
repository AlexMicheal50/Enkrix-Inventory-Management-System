<?php

declare(strict_types=1);

namespace App\Config;

class Config
{
    private static array $config = [];

    public static function get(string $key, mixed $default = null): mixed
    {
        if (empty(self::$config)) {
            self::$config = [
                'app' => [
                    'env'  => $_ENV['APP_ENV']  ?? 'production',
                    'url'  => $_ENV['APP_URL']  ?? 'http://localhost:8000',
                    'key'  => $_ENV['APP_KEY']  ?? '',
                    'name' => 'Enkrix IMS',
                ],
                'db' => [
                    'host' => $_ENV['DB_HOST'] ?? 'db',
                    'port' => $_ENV['DB_PORT'] ?? '3306',
                    'name' => $_ENV['DB_NAME'] ?? 'enkrix_inventory',
                    'user' => $_ENV['DB_USER'] ?? 'root',
                    'pass' => $_ENV['DB_PASS'] ?? '',
                ],
                'pagination' => [
                    'per_page' => 15,
                ],
                'upload' => [
                    'path'      => BASE_PATH . '/public/uploads/',
                    'max_size'  => 5 * 1024 * 1024,
                    'allowed'   => ['image/jpeg', 'image/png', 'image/webp'],
                ],
            ];
        }

        $keys  = explode('.', $key);
        $value = self::$config;
        foreach ($keys as $k) {
            $value = $value[$k] ?? null;
            if ($value === null) return $default;
        }
        return $value;
    }
}
