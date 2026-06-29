<?php

declare(strict_types=1);

namespace App\Models;

class InventoryItem extends BaseModel
{
    protected string $table = 'inventory_items';

    public function paginated(int $offset, int $limit, array $filters = []): array
    {
        $where  = ['1=1'];
        $params = [];

        if (!empty($filters['search'])) {
            $where[]  = '(i.name LIKE ? OR i.location LIKE ? OR i.barcode LIKE ?)';
            $term     = '%' . $filters['search'] . '%';
            $params[] = $term; $params[] = $term; $params[] = $term;
        }
        if (!empty($filters['category_id'])) {
            $where[]  = 'i.category_id = ?';
            $params[] = $filters['category_id'];
        }
        if (!empty($filters['condition'])) {
            $where[]  = 'i.condition_status = ?';
            $params[] = $filters['condition'];
        }
        if (!empty($filters['location'])) {
            $where[]  = 'i.location LIKE ?';
            $params[] = '%' . $filters['location'] . '%';
        }

        $whereStr = implode(' AND ', $where);
        $params[] = $limit;
        $params[] = $offset;

        return $this->query(
            "SELECT i.*, c.name AS category_name, c.color AS category_color,
                    (i.quantity - i.quantity_assigned) AS available
             FROM inventory_items i
             JOIN categories c ON c.id = i.category_id
             WHERE {$whereStr}
             ORDER BY i.updated_at DESC
             LIMIT ? OFFSET ?",
            $params
        )->fetchAll();
    }

    public function countFiltered(array $filters = []): int
    {
        $where  = ['1=1'];
        $params = [];

        if (!empty($filters['search'])) {
            $where[]  = '(i.name LIKE ? OR i.location LIKE ? OR i.barcode LIKE ?)';
            $term     = '%' . $filters['search'] . '%';
            $params[] = $term; $params[] = $term; $params[] = $term;
        }
        if (!empty($filters['category_id'])) {
            $where[]  = 'i.category_id = ?';
            $params[] = $filters['category_id'];
        }
        if (!empty($filters['condition'])) {
            $where[]  = 'i.condition_status = ?';
            $params[] = $filters['condition'];
        }

        return (int)$this->query(
            "SELECT COUNT(*) FROM inventory_items i WHERE " . implode(' AND ', $where),
            $params
        )->fetchColumn();
    }

    public function findWithCategory(int $id): ?array
    {
        $row = $this->query(
            "SELECT i.*, c.name AS category_name, c.color AS category_color,
                    (i.quantity - i.quantity_assigned) AS available
             FROM inventory_items i
             JOIN categories c ON c.id = i.category_id
             WHERE i.id = ? LIMIT 1",
            [$id]
        )->fetch();
        return $row ?: null;
    }

    public function create(array $data): int
    {
        $this->query(
            "INSERT INTO inventory_items
             (name, category_id, description, quantity, unit, condition_status,
              location, purchase_date, cost, selling_price, low_stock_threshold, image, barcode, created_by)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [
                $data['name'],
                $data['category_id'],
                $data['description']         ?? null,
                $data['quantity'],
                $data['unit']                ?? null,
                $data['condition_status'],
                $data['location']            ?? null,
                $data['purchase_date']       ?: null,
                $data['cost']                ?? 0,
                $data['selling_price']       ?? 0,
                $data['low_stock_threshold'] ?? 5,
                $data['image']               ?? null,
                $data['barcode']             ?: null,
                $data['created_by'],
            ]
        );
        return $this->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $sets   = ['name=?','category_id=?','description=?','quantity=?','unit=?',
                   'condition_status=?','location=?','purchase_date=?','cost=?',
                   'selling_price=?','low_stock_threshold=?','barcode=?'];
        $params = [
            $data['name'],         $data['category_id'],
            $data['description']   ?? null,
            $data['quantity'],     $data['unit'] ?? null,
            $data['condition_status'],
            $data['location']      ?? null,
            $data['purchase_date'] ?: null,
            $data['cost']          ?? 0,
            $data['selling_price'] ?? 0,
            $data['low_stock_threshold'] ?? 5,
            $data['barcode']       ?: null,
        ];

        if (array_key_exists('image', $data)) {
            $sets[]   = 'image=?';
            $params[] = $data['image']; // null clears it, string sets it
        }

        $params[] = $id;
        return (bool)$this->query(
            "UPDATE inventory_items SET " . implode(',', $sets) . " WHERE id = ?",
            $params
        )->rowCount();
    }

    public function lowStockItems(): array
    {
        return $this->query(
            "SELECT i.*, c.name AS category_name,
                    (i.quantity - i.quantity_assigned) AS available
             FROM inventory_items i
             JOIN categories c ON c.id = i.category_id
             WHERE (i.quantity - i.quantity_assigned) <= i.low_stock_threshold
             ORDER BY (i.quantity - i.quantity_assigned) ASC"
        )->fetchAll();
    }

    public function dashboardStats(): array
    {
        return $this->query(
            "SELECT
               COUNT(*)                        AS total_items,
               SUM(quantity)                   AS total_quantity,
               SUM(quantity_assigned)          AS total_assigned,
               SUM(cost * quantity)            AS total_value,
               SUM(CASE WHEN (quantity - quantity_assigned) <= low_stock_threshold THEN 1 ELSE 0 END) AS low_stock_count
             FROM inventory_items"
        )->fetch();
    }

    public function recentItems(int $limit = 5): array
    {
        return $this->query(
            "SELECT i.*, c.name AS category_name
             FROM inventory_items i
             JOIN categories c ON c.id = i.category_id
             ORDER BY i.created_at DESC LIMIT ?",
            [$limit]
        )->fetchAll();
    }

    public function updateAssignedQty(int $id, int $delta): void
    {
        $this->query(
            "UPDATE inventory_items SET quantity_assigned = quantity_assigned + ? WHERE id = ?",
            [$delta, $id]
        );
    }

    public function allForReport(): array
    {
        return $this->query(
            "SELECT i.*, c.name AS category_name,
                    (i.quantity - i.quantity_assigned) AS available
             FROM inventory_items i
             JOIN categories c ON c.id = i.category_id
             ORDER BY c.name, i.name"
        )->fetchAll();
    }

    public function locations(): array
    {
        return $this->query(
            "SELECT DISTINCT location FROM inventory_items WHERE location IS NOT NULL ORDER BY location"
        )->fetchAll(\PDO::FETCH_COLUMN);
    }
}
