<?php

declare(strict_types=1);

namespace App\Models;

class Category extends BaseModel
{
    protected string $table = 'categories';

    public function allWithCount(): array
    {
        return $this->query(
            "SELECT c.*, COUNT(i.id) AS item_count
             FROM categories c
             LEFT JOIN inventory_items i ON i.category_id = c.id
             GROUP BY c.id
             ORDER BY c.name ASC"
        )->fetchAll();
    }

    public function create(array $data): int
    {
        $this->query(
            "INSERT INTO categories (name, description, color, created_by) VALUES (?, ?, ?, ?)",
            [
                $data['name'],
                $data['description'] ?? null,
                $data['color'] ?? '#D4A853',
                $data['created_by'],
            ]
        );
        return $this->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        return (bool)$this->query(
            "UPDATE categories SET name = ?, description = ?, color = ? WHERE id = ?",
            [$data['name'], $data['description'] ?? null, $data['color'] ?? '#D4A853', $id]
        )->rowCount();
    }

    public function canDelete(int $id): bool
    {
        $count = (int)$this->query(
            "SELECT COUNT(*) FROM inventory_items WHERE category_id = ?",
            [$id]
        )->fetchColumn();
        return $count === 0;
    }
}
