<?php

declare(strict_types=1);

namespace App\Models;

class User extends BaseModel
{
    protected string $table = 'users';

    public function findByEmail(string $email): ?array
    {
        $row = $this->query(
            "SELECT u.*, r.name AS role FROM users u
             JOIN roles r ON r.id = u.role_id
             WHERE u.email = ? LIMIT 1",
            [$email]
        )->fetch();
        return $row ?: null;
    }

    public function allWithRoles(): array
    {
        return $this->query(
            "SELECT u.*, r.name AS role FROM users u
             JOIN roles r ON r.id = u.role_id
             ORDER BY u.created_at DESC"
        )->fetchAll();
    }

    public function create(array $data): int
    {
        $this->query(
            "INSERT INTO users (name, email, password, role_id) VALUES (?, ?, ?, ?)",
            [
                $data['name'],
                $data['email'],
                password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12]),
                $data['role_id'],
            ]
        );
        return $this->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $sets   = ['name = ?', 'email = ?', 'role_id = ?'];
        $params = [$data['name'], $data['email'], $data['role_id']];

        if (!empty($data['password'])) {
            $sets[]   = 'password = ?';
            $params[] = password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12]);
        }

        $params[] = $id;
        return (bool)$this->query(
            "UPDATE users SET " . implode(', ', $sets) . " WHERE id = ?",
            $params
        )->rowCount();
    }

    public function toggleActive(int $id): void
    {
        $this->query("UPDATE users SET is_active = NOT is_active WHERE id = ?", [$id]);
    }

    public function updateLastLogin(int $id): void
    {
        $this->query("UPDATE users SET last_login = NOW() WHERE id = ?", [$id]);
    }

    public function allRoles(): array
    {
        return $this->query("SELECT * FROM roles ORDER BY id")->fetchAll();
    }
}
