<?php

declare(strict_types=1);

namespace App\Models;

use App\Config\Database;
use PDO;
use PDOStatement;

abstract class BaseModel
{
    protected PDO $db;
    protected string $table;
    protected string $primaryKey = 'id';

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    protected function query(string $sql, array $params = []): PDOStatement
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->query(
            "SELECT * FROM `{$this->table}` WHERE `{$this->primaryKey}` = ? LIMIT 1",
            [$id]
        );
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function all(string $orderBy = 'id', string $direction = 'ASC'): array
    {
        return $this->query(
            "SELECT * FROM `{$this->table}` ORDER BY `{$orderBy}` {$direction}"
        )->fetchAll();
    }

    public function count(string $where = '', array $params = []): int
    {
        $sql = "SELECT COUNT(*) FROM `{$this->table}`";
        if ($where) $sql .= " WHERE {$where}";
        return (int)$this->query($sql, $params)->fetchColumn();
    }

    public function delete(int $id): bool
    {
        return (bool)$this->query(
            "DELETE FROM `{$this->table}` WHERE `{$this->primaryKey}` = ?",
            [$id]
        )->rowCount();
    }

    protected function lastInsertId(): int
    {
        return (int)$this->db->lastInsertId();
    }
}
