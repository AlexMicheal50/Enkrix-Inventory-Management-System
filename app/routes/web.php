<?php

declare(strict_types=1);

use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\InventoryController;
use App\Controllers\CategoryController;
use App\Controllers\AssignmentController;
use App\Controllers\ReportController;
use App\Controllers\UserController;
use App\Controllers\SalesController;
use App\Controllers\ExpensesController;
use App\Middleware\AuthMiddleware;

$uri    = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '', '/');
$method = $_SERVER['REQUEST_METHOD'];

// Strip base segments if deployed in subdirectory
$basePath = trim(parse_url(\App\Config\Config::get('app.url'), PHP_URL_PATH) ?? '', '/');
if ($basePath && str_starts_with($uri, $basePath)) {
    $uri = trim(substr($uri, strlen($basePath)), '/');
}

// Simple router
function matchRoute(string $pattern, string $uri): array|false
{
    $regex = preg_replace('/\{([a-z_]+)\}/', '([^/]+)', $pattern);
    $regex = '@^' . $regex . '$@';
    if (preg_match($regex, $uri, $m)) {
        array_shift($m);
        return $m;
    }
    return false;
}

$routes = [
    // Auth
    ['GET',  'login',                   [AuthController::class, 'showLogin'],  false],
    ['POST', 'login',                   [AuthController::class, 'login'],      false],
    ['POST', 'logout',                  [AuthController::class, 'logout'],     true],

    // Dashboard
    ['GET',  '',                        [DashboardController::class, 'index'], true],
    ['GET',  'dashboard',               [DashboardController::class, 'index'], true],

    // Inventory
    ['GET',  'inventory',               [InventoryController::class, 'index'],   true],
    ['GET',  'inventory/create',        [InventoryController::class, 'create'],  true],
    ['POST', 'inventory/store',         [InventoryController::class, 'store'],   true],
    ['GET',  'inventory/{id}',          [InventoryController::class, 'show'],    true],
    ['GET',  'inventory/{id}/edit',     [InventoryController::class, 'edit'],    true],
    ['POST', 'inventory/{id}/update',   [InventoryController::class, 'update'],  true],
    ['POST', 'inventory/{id}/delete',   [InventoryController::class, 'delete'],  true],

    // Categories
    ['GET',  'categories',              [CategoryController::class, 'index'],  true],
    ['POST', 'categories/store',        [CategoryController::class, 'store'],  true],
    ['POST', 'categories/{id}/update',  [CategoryController::class, 'update'], true],
    ['POST', 'categories/{id}/delete',  [CategoryController::class, 'delete'], true],

    // Assignments
    ['GET',  'assignments',             [AssignmentController::class, 'index'],  true],
    ['GET',  'assignments/create',      [AssignmentController::class, 'create'], true],
    ['POST', 'assignments/store',       [AssignmentController::class, 'store'],  true],
    ['POST', 'assignments/{id}/return', [AssignmentController::class, 'return'], true],

    // Reports
    ['GET',  'reports',                 [ReportController::class, 'index'],  true],
    ['GET',  'reports/export',          [ReportController::class, 'export'], true],

    // Users
    ['GET',  'users',                   [UserController::class, 'index'],        true],
    ['POST', 'users/store',             [UserController::class, 'store'],        true],
    ['POST', 'users/{id}/update',       [UserController::class, 'update'],       true],
    ['POST', 'users/{id}/toggle',       [UserController::class, 'toggle'],       true],
    ['POST', 'users/{id}/delete',       [UserController::class, 'delete'],       true],

    // Sales
    ['GET',  'sales',                   [SalesController::class, 'index'],   true],
    ['GET',  'sales/create',            [SalesController::class, 'create'],  true],
    ['POST', 'sales/store',             [SalesController::class, 'store'],   true],
    ['GET',  'sales/report',            [SalesController::class, 'report'],  true],
    ['GET',  'sales/export',            [SalesController::class, 'export'],  true],
    ['POST', 'sales/{id}/update',       [SalesController::class, 'update'],  true],
    ['POST', 'sales/{id}/delete',       [SalesController::class, 'delete'],  true],

    // Expenses
    ['GET',  'expenses',                [ExpensesController::class, 'index'],        true],
    ['POST', 'expenses/store',          [ExpensesController::class, 'store'],        true],
    ['POST', 'expenses/{id}/update',    [ExpensesController::class, 'update'],       true],
    ['POST', 'expenses/{id}/delete',    [ExpensesController::class, 'delete'],       true],

    // Activity log
    ['GET',  'activity',                [DashboardController::class, 'activity'], true],
];

$matched = false;
foreach ($routes as [$routeMethod, $pattern, $handler, $requiresAuth]) {
    if ($routeMethod !== $method) continue;
    $params = matchRoute($pattern, $uri);
    if ($params !== false) {
        if ($requiresAuth) {
            AuthMiddleware::handle();
        }
        [$class, $action] = $handler;
        $controller = new $class();
        $controller->$action(...$params);
        $matched = true;
        break;
    }
}

if (!$matched) {
    http_response_code(404);
    if (is_auth()) {
        view('errors.404');
    } else {
        redirect('login');
    }
}
