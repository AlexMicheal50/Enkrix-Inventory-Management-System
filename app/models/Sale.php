<?php

declare(strict_types=1);

namespace App\Models;

class Sale extends BaseModel
{
    protected string $table = 'sales';

    public function create(array $data): int
    {
        $qty     = (int)$data['quantity_sold'];
        $cost    = (float)$data['cost_price'];
        $sell    = (float)$data['selling_price'];

        $this->query(
            "INSERT INTO sales
             (item_id, item_name, quantity_sold, cost_price, selling_price,
              total_cost, total_revenue, profit, sold_by, sale_date, notes)
             VALUES (?,?,?,?,?,?,?,?,?,?,?)",
            [
                $data['item_id'], $data['item_name'], $qty,
                $cost, $sell,
                round($cost * $qty, 2),
                round($sell * $qty, 2),
                round(($sell - $cost) * $qty, 2),
                $data['sold_by'], $data['sale_date'],
                $data['notes'] ?? null,
            ]
        );
        return $this->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $qty  = (int)$data['quantity_sold'];
        $cost = (float)$data['cost_price'];
        $sell = (float)$data['selling_price'];

        return (bool)$this->query(
            "UPDATE sales SET quantity_sold=?, selling_price=?,
             total_cost=?, total_revenue=?, profit=?, sale_date=?, notes=?
             WHERE id=?",
            [
                $qty, $sell,
                round($cost * $qty, 2),
                round($sell * $qty, 2),
                round(($sell - $cost) * $qty, 2),
                $data['sale_date'],
                $data['notes'] ?? null,
                $id,
            ]
        )->rowCount();
    }

    public function paginated(int $offset, int $limit, array $filters = []): array
    {
        $where = ['1=1']; $params = [];
        if (!empty($filters['search'])) {
            $where[] = 'item_name LIKE ?';
            $params[] = '%' . $filters['search'] . '%';
        }
        if (!empty($filters['date_from'])) { $where[] = 'sale_date >= ?'; $params[] = $filters['date_from']; }
        if (!empty($filters['date_to']))   { $where[] = 'sale_date <= ?'; $params[] = $filters['date_to']; }
        $params[] = $limit; $params[] = $offset;

        return $this->query(
            "SELECT s.*, u.name AS sold_by_name FROM sales s
             JOIN users u ON u.id = s.sold_by
             WHERE " . implode(' AND ', $where) . "
             ORDER BY s.sale_date DESC, s.created_at DESC LIMIT ? OFFSET ?",
            $params
        )->fetchAll();
    }

    public function countFiltered(array $filters = []): int
    {
        $where = ['1=1']; $params = [];
        if (!empty($filters['search'])) { $where[] = 'item_name LIKE ?'; $params[] = '%' . $filters['search'] . '%'; }
        if (!empty($filters['date_from'])) { $where[] = 'sale_date >= ?'; $params[] = $filters['date_from']; }
        if (!empty($filters['date_to']))   { $where[] = 'sale_date <= ?'; $params[] = $filters['date_to']; }
        return (int)$this->query("SELECT COUNT(*) FROM sales WHERE " . implode(' AND ', $where), $params)->fetchColumn();
    }

    public function summaryForPeriod(string $from, string $to): array
    {
        return $this->query(
            "SELECT COUNT(*) AS total_transactions,
               COALESCE(SUM(quantity_sold),0)  AS total_units,
               COALESCE(SUM(total_revenue),0)  AS total_revenue,
               COALESCE(SUM(total_cost),0)     AS total_cost,
               COALESCE(SUM(profit),0)         AS total_profit
             FROM sales WHERE sale_date BETWEEN ? AND ?",
            [$from, $to]
        )->fetch();
    }

    public function dailyBreakdown(string $from, string $to): array
    {
        return $this->query(
            "SELECT sale_date,
               SUM(quantity_sold) AS units,
               SUM(total_revenue) AS revenue,
               SUM(total_cost)    AS cost,
               SUM(profit)        AS profit
             FROM sales WHERE sale_date BETWEEN ? AND ?
             GROUP BY sale_date ORDER BY sale_date ASC",
            [$from, $to]
        )->fetchAll();
    }

    public function topItems(string $from, string $to, int $limit = 5): array
    {
        return $this->query(
            "SELECT item_name, item_id,
               SUM(quantity_sold) AS units,
               SUM(total_revenue) AS revenue,
               SUM(profit)        AS profit
             FROM sales WHERE sale_date BETWEEN ? AND ?
             GROUP BY item_id, item_name ORDER BY revenue DESC LIMIT ?",
            [$from, $to, $limit]
        )->fetchAll();
    }

    public function totalRevenue(): float
    {
        return (float)$this->query("SELECT COALESCE(SUM(total_revenue),0) FROM sales")->fetchColumn();
    }

    public function totalProfit(): float
    {
        return (float)$this->query("SELECT COALESCE(SUM(profit),0) FROM sales")->fetchColumn();
    }
}
