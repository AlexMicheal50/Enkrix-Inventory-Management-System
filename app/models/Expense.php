<?php

declare(strict_types=1);

namespace App\Models;

class Expense extends BaseModel
{
    protected string $table = 'expenses';

    public function create(array $data): int
    {
        $this->query(
            "INSERT INTO expenses (title, category, amount, expense_date, description, recorded_by)
             VALUES (?,?,?,?,?,?)",
            [
                $data['title'], $data['category'] ?? 'General',
                $data['amount'], $data['expense_date'],
                $data['description'] ?? null, $data['recorded_by'],
            ]
        );
        return $this->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        return (bool)$this->query(
            "UPDATE expenses SET title=?, category=?, amount=?, expense_date=?, description=? WHERE id=?",
            [$data['title'], $data['category'], $data['amount'], $data['expense_date'], $data['description'] ?? null, $id]
        )->rowCount();
    }

    public function paginated(int $offset, int $limit, array $filters = []): array
    {
        $where = ['1=1']; $params = [];
        if (!empty($filters['search'])) { $where[] = '(title LIKE ? OR category LIKE ?)'; $t = '%'.$filters['search'].'%'; $params[] = $t; $params[] = $t; }
        if (!empty($filters['date_from'])) { $where[] = 'expense_date >= ?'; $params[] = $filters['date_from']; }
        if (!empty($filters['date_to']))   { $where[] = 'expense_date <= ?'; $params[] = $filters['date_to']; }
        $params[] = $limit; $params[] = $offset;

        return $this->query(
            "SELECT e.*, u.name AS recorded_by_name FROM expenses e
             JOIN users u ON u.id = e.recorded_by
             WHERE " . implode(' AND ', $where) . "
             ORDER BY e.expense_date DESC, e.created_at DESC LIMIT ? OFFSET ?",
            $params
        )->fetchAll();
    }

    public function countFiltered(array $filters = []): int
    {
        $where = ['1=1']; $params = [];
        if (!empty($filters['search'])) { $where[] = '(title LIKE ? OR category LIKE ?)'; $t = '%'.$filters['search'].'%'; $params[] = $t; $params[] = $t; }
        if (!empty($filters['date_from'])) { $where[] = 'expense_date >= ?'; $params[] = $filters['date_from']; }
        if (!empty($filters['date_to']))   { $where[] = 'expense_date <= ?'; $params[] = $filters['date_to']; }
        return (int)$this->query("SELECT COUNT(*) FROM expenses WHERE " . implode(' AND ', $where), $params)->fetchColumn();
    }

    public function totalForPeriod(string $from, string $to): float
    {
        return (float)$this->query(
            "SELECT COALESCE(SUM(amount),0) FROM expenses WHERE expense_date BETWEEN ? AND ?",
            [$from, $to]
        )->fetchColumn();
    }

    public function totalAll(): float
    {
        return (float)$this->query("SELECT COALESCE(SUM(amount),0) FROM expenses")->fetchColumn();
    }

    public function categories(): array
    {
        return $this->query("SELECT DISTINCT category FROM expenses ORDER BY category")->fetchAll(\PDO::FETCH_COLUMN);
    }
}
