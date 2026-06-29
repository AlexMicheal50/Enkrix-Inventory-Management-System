<?php

declare(strict_types=1);

namespace App\Models;

class Assignment extends BaseModel
{
    protected string $table = 'assignments';

    public function paginated(int $offset, int $limit, array $filters = []): array
    {
        $where  = ['1=1'];
        $params = [];

        if (!empty($filters['status'])) {
            $where[]  = 'a.status = ?';
            $params[] = $filters['status'];
        }
        if (!empty($filters['search'])) {
            $where[]  = '(i.name LIKE ? OR a.assigned_to_name LIKE ?)';
            $term     = '%' . $filters['search'] . '%';
            $params[] = $term; $params[] = $term;
        }

        $whereStr = implode(' AND ', $where);
        $params[] = $limit;
        $params[] = $offset;

        return $this->query(
            "SELECT a.*, i.name AS item_name, u.name AS assigned_by_name
             FROM assignments a
             JOIN inventory_items i ON i.id = a.item_id
             JOIN users u ON u.id = a.assigned_by
             WHERE {$whereStr}
             ORDER BY a.created_at DESC
             LIMIT ? OFFSET ?",
            $params
        )->fetchAll();
    }

    public function countFiltered(array $filters = []): int
    {
        $where  = ['1=1'];
        $params = [];

        if (!empty($filters['status'])) {
            $where[]  = 'a.status = ?';
            $params[] = $filters['status'];
        }
        if (!empty($filters['search'])) {
            $where[]  = '(i.name LIKE ? OR a.assigned_to_name LIKE ?)';
            $term     = '%' . $filters['search'] . '%';
            $params[] = $term; $params[] = $term;
        }

        return (int)$this->query(
            "SELECT COUNT(*) FROM assignments a
             JOIN inventory_items i ON i.id = a.item_id
             WHERE " . implode(' AND ', $where),
            $params
        )->fetchColumn();
    }

    public function create(array $data): int
    {
        $this->query(
            "INSERT INTO assignments
             (item_id, assigned_to_type, assigned_to_name, quantity_assigned,
              assigned_by, assignment_date, expected_return_date, notes)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
            [
                $data['item_id'],
                $data['assigned_to_type'],
                $data['assigned_to_name'],
                $data['quantity_assigned'],
                $data['assigned_by'],
                $data['assignment_date'],
                $data['expected_return_date'] ?: null,
                $data['notes'] ?? null,
            ]
        );
        return $this->lastInsertId();
    }

    public function returnItem(int $id): bool
    {
        return (bool)$this->query(
            "UPDATE assignments SET status = 'returned', actual_return_date = CURDATE() WHERE id = ?",
            [$id]
        )->rowCount();
    }

    public function markOverdue(): void
    {
        $this->query(
            "UPDATE assignments
             SET status = 'overdue'
             WHERE status = 'active'
               AND expected_return_date IS NOT NULL
               AND expected_return_date < CURDATE()"
        );
    }

    public function stats(): array
    {
        return $this->query(
            "SELECT
               COUNT(*)                                              AS total,
               SUM(CASE WHEN status='active'   THEN 1 ELSE 0 END)  AS active,
               SUM(CASE WHEN status='returned' THEN 1 ELSE 0 END)  AS returned,
               SUM(CASE WHEN status='overdue'  THEN 1 ELSE 0 END)  AS overdue
             FROM assignments"
        )->fetch();
    }

    public function recentActive(int $limit = 5): array
    {
        return $this->query(
            "SELECT a.*, i.name AS item_name, u.name AS assigned_by_name
             FROM assignments a
             JOIN inventory_items i ON i.id = a.item_id
             JOIN users u ON u.id = a.assigned_by
             WHERE a.status = 'active'
             ORDER BY a.created_at DESC LIMIT ?",
            [$limit]
        )->fetchAll();
    }

    public function allForReport(): array
    {
        return $this->query(
            "SELECT a.*, i.name AS item_name, c.name AS category_name, u.name AS assigned_by_name
             FROM assignments a
             JOIN inventory_items i ON i.id = a.item_id
             JOIN categories c ON c.id = i.category_id
             JOIN users u ON u.id = a.assigned_by
             ORDER BY a.assignment_date DESC"
        )->fetchAll();
    }
}
