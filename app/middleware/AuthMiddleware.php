<?php

declare(strict_types=1);

namespace App\Middleware;

class AuthMiddleware
{
    public static function handle(): void
    {
        if (!is_auth()) {
            flash('error', 'Please login to continue.');
            redirect('login');
        }
    }

    public static function requireRole(string ...$roles): void
    {
        self::handle();
        if (!has_role(...$roles)) {
            flash('error', 'You do not have permission to access this resource.');
            redirect('dashboard');
        }
    }
}
