<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\User;
use App\Models\ActivityLog;

class AuthController extends BaseController
{
    public function showLogin(): void
    {
        if (is_auth()) redirect('dashboard');
        $this->render('auth.login');
    }

    public function login(): void
    {
        $this->verifyCsrf();

        $email    = strtolower(trim($this->input('email')));
        $password = $this->input('password');

        $errors = [];
        if (empty($email))    $errors[] = 'Email is required.';
        if (empty($password)) $errors[] = 'Password is required.';

        if ($errors) {
            $_SESSION['_old'] = ['email' => $email];
            flash('error', implode(' ', $errors));
            redirect('login');
        }

        $userModel = new User();
        $user      = $userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            $_SESSION['_old'] = ['email' => $email];
            flash('error', 'Invalid email or password.');
            redirect('login');
        }

        if (!$user['is_active']) {
            flash('error', 'Your account has been deactivated. Contact an administrator.');
            redirect('login');
        }

        $_SESSION['_user'] = [
            'id'    => $user['id'],
            'name'  => $user['name'],
            'email' => $user['email'],
            'role'  => $user['role'],
        ];

        $userModel->updateLastLogin((int)$user['id']);
        ActivityLog::log('login', 'auth', (int)$user['id'], $user['name'], 'User logged in');

        session_regenerate_id(true);
        redirect('dashboard');
    }

    public function logout(): void
    {
        $this->verifyCsrf();
        $user = auth();
        if ($user) {
            ActivityLog::log('logout', 'auth', (int)$user['id'], $user['name'], 'User logged out');
        }
        $_SESSION = [];
        session_destroy();
        redirect('login');
    }
}
