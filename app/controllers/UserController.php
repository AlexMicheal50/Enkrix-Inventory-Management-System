<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\User;
use App\Models\ActivityLog;
use App\Middleware\AuthMiddleware;

class UserController extends BaseController
{
    private User $users;

    public function __construct()
    {
        $this->users = new User();
    }

    public function index(): void
    {
        AuthMiddleware::requireRole('Admin');
        $users = $this->users->allWithRoles();
        $roles = $this->users->allRoles();
        $this->render('users.index', compact('users', 'roles'));
    }

    public function store(): void
    {
        AuthMiddleware::requireRole('Admin');
        $this->verifyCsrf();

        $name     = $this->sanitize('name');
        $email    = strtolower(trim($this->input('email')));
        $password = $this->input('password');
        $roleId   = (int)$this->input('role_id');

        $errors = [];
        if (empty($name))     $errors[] = 'Name is required.';
        if (empty($email))    $errors[] = 'Email is required.';
        if (empty($password)) $errors[] = 'Password is required.';
        if (strlen($password) < 8) $errors[] = 'Password must be at least 8 characters.';
        if (!$roleId)         $errors[] = 'Role is required.';
        if ($this->users->findByEmail($email)) $errors[] = 'Email already exists.';

        if ($errors) {
            flash('error', implode('<br>', $errors));
            redirect('users');
        }

        $id = $this->users->create(['name' => $name, 'email' => $email, 'password' => $password, 'role_id' => $roleId]);
        ActivityLog::log('created', 'user', $id, $name, 'User account created');
        $this->redirect('users', 'success', "User \"{$name}\" created.");
    }

    public function update(string $id): void
    {
        AuthMiddleware::requireRole('Admin');
        $this->verifyCsrf();

        $name     = $this->sanitize('name');
        $email    = strtolower(trim($this->input('email')));
        $password = $this->input('password');
        $roleId   = (int)$this->input('role_id');

        $errors = [];
        if (empty($name))  $errors[] = 'Name is required.';
        if (empty($email)) $errors[] = 'Email is required.';
        if (!empty($password) && strlen($password) < 8) {
            $errors[] = 'Password must be at least 8 characters.';
        }

        if ($errors) {
            flash('error', implode('<br>', $errors));
            redirect('users');
        }

        $this->users->update((int)$id, [
            'name' => $name, 'email' => $email, 'password' => $password, 'role_id' => $roleId
        ]);
        ActivityLog::log('updated', 'user', (int)$id, $name, 'User account updated');
        $this->redirect('users', 'success', "User \"{$name}\" updated.");
    }

    public function toggle(string $id): void
    {
        AuthMiddleware::requireRole('Admin');
        $this->verifyCsrf();

        if ((int)$id === auth()['id']) {
            $this->redirect('users', 'error', 'You cannot deactivate your own account.');
        }

        $user = $this->users->findById((int)$id);
        if (!$user) { http_response_code(404); die('User not found.'); }

        $this->users->toggleActive((int)$id);
        $action = $user['is_active'] ? 'deactivated' : 'activated';
        ActivityLog::log($action, 'user', (int)$id, $user['name'], "User {$action}");
        $this->redirect('users', 'success', "User \"{$user['name']}\" {$action}.");
    }

    public function delete(string $id): void
    {
        AuthMiddleware::requireRole('Admin');
        $this->verifyCsrf();

        if ((int)$id === auth()['id']) {
            $this->redirect('users', 'error', 'You cannot delete your own account.');
        }

        $user = $this->users->findById((int)$id);
        if (!$user) { http_response_code(404); die('User not found.'); }

        $this->users->delete((int)$id);
        ActivityLog::log('deleted', 'user', (int)$id, $user['name'], 'User account deleted');
        $this->redirect('users', 'success', "User \"{$user['name']}\" deleted.");
    }
}
