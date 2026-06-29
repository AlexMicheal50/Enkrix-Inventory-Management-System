<?php

declare(strict_types=1);

namespace App\Controllers;

abstract class BaseController
{
    protected function render(string $template, array $data = []): void
    {
        view($template, $data);
    }

    protected function redirect(string $path, string $type = '', string $message = ''): never
    {
        if ($type && $message) {
            flash($type, $message);
        }
        redirect($path);
    }

    protected function input(string $key, mixed $default = ''): mixed
    {
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }

    protected function sanitize(string $key): string
    {
        return trim(strip_tags($this->input($key, '')));
    }

    protected function verifyCsrf(): void
    {
        if (!csrf_verify()) {
            http_response_code(419);
            die('CSRF token mismatch.');
        }
    }

    protected function currentPage(): int
    {
        return max(1, (int)($this->input('page', 1)));
    }

    protected function json(mixed $data, int $code = 200): never
    {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
