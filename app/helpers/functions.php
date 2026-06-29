<?php

declare(strict_types=1);

function url(string $path = ''): string
{
    $base = rtrim(\App\Config\Config::get('app.url'), '/');
    return $base . '/' . ltrim($path, '/');
}

function asset(string $path): string
{
    return url('assets/' . ltrim($path, '/'));
}

function redirect(string $path, int $code = 302): never
{
    header('Location: ' . url($path), true, $code);
    exit;
}

function view(string $template, array $data = []): void
{
    extract($data, EXTR_SKIP);
    $file = APP_PATH . '/views/' . str_replace('.', '/', $template) . '.php';
    if (!file_exists($file)) {
        throw new \RuntimeException("View [{$template}] not found.");
    }
    require $file;
}

function csrf_token(): string
{
    if (empty($_SESSION['_csrf_token'])) {
        $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="_csrf_token" value="' . htmlspecialchars(csrf_token(), ENT_QUOTES) . '">';
}

function csrf_verify(): bool
{
    $token = $_POST['_csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    return hash_equals($_SESSION['_csrf_token'] ?? '', $token);
}

function e(mixed $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function old(string $key, mixed $default = ''): string
{
    return e($_SESSION['_old'][$key] ?? $default);
}

function flash(string $key, mixed $value = null): mixed
{
    if ($value !== null) {
        $_SESSION['_flash'][$key] = $value;
        return null;
    }
    $val = $_SESSION['_flash'][$key] ?? null;
    unset($_SESSION['_flash'][$key]);
    return $val;
}

function auth(): ?array
{
    return $_SESSION['_user'] ?? null;
}

function is_auth(): bool
{
    return isset($_SESSION['_user']);
}

function has_role(string ...$roles): bool
{
    $user = auth();
    if (!$user) return false;
    return in_array($user['role'], $roles, true);
}

function can(string $permission): bool
{
    $user = auth();
    if (!$user) return false;
    $rolePermissions = [
        'Admin'             => ['*'],
        'Inventory Manager' => ['inventory.*', 'categories.*', 'assignments.*', 'reports.*', 'dashboard.*'],
        'Viewer'            => ['reports.*', 'dashboard.*', 'inventory.view', 'categories.view', 'assignments.view'],
    ];
    $perms = $rolePermissions[$user['role']] ?? [];
    if (in_array('*', $perms, true)) return true;
    foreach ($perms as $p) {
        if ($p === $permission) return true;
        if (str_ends_with($p, '.*') && str_starts_with($permission, rtrim($p, '*'))) return true;
    }
    return false;
}

function format_currency(float $amount): string
{
    return '£' . number_format($amount, 2);
}

function format_date(string|null $date, string $format = 'd M Y'): string
{
    if (!$date) return '—';
    return date($format, strtotime($date));
}

function condition_badge(string $condition): string
{
    $map = [
        'New'     => 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30',
        'Good'    => 'bg-blue-500/20 text-blue-400 border border-blue-500/30',
        'Fair'    => 'bg-amber-500/20 text-amber-400 border border-amber-500/30',
        'Damaged' => 'bg-red-500/20 text-red-400 border border-red-500/30',
    ];
    $cls = $map[$condition] ?? 'bg-gray-500/20 text-gray-400';
    return '<span class="px-2 py-0.5 rounded text-xs font-medium ' . $cls . '">' . e($condition) . '</span>';
}

function status_badge(string $status): string
{
    $map = [
        'active'   => 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30',
        'returned' => 'bg-blue-500/20 text-blue-400 border border-blue-500/30',
        'overdue'  => 'bg-red-500/20 text-red-400 border border-red-500/30',
    ];
    $cls = $map[$status] ?? 'bg-gray-500/20 text-gray-400';
    return '<span class="px-2 py-0.5 rounded text-xs font-medium ' . $cls . '">' . e(ucfirst($status)) . '</span>';
}

function paginate(int $total, int $page, int $perPage): array
{
    $totalPages = (int)ceil($total / $perPage);
    return [
        'total'       => $total,
        'per_page'    => $perPage,
        'current'     => $page,
        'total_pages' => $totalPages,
        'offset'      => ($page - 1) * $perPage,
        'has_prev'    => $page > 1,
        'has_next'    => $page < $totalPages,
    ];
}
