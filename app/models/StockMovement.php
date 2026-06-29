<?php

declare(strict_types=1);

namespace App\Models;

class StockMovement extends BaseModel
{
    protected string $table = 'stock_movements';

    public static function record(
        int    $itemId,
        string $itemName,
        string $type,
        int    $change,
        int    $before,
        string $refType  = '',
        int    $refId    = 0,
        string $notes    = ''
    ): void {
        $db   = \App\Config\Database::getInstance();
        $user = auth();

        $db->prepare(
            "INSERT INTO stock_movements
             (item_id, item_name, movement_type, quantity_change, quantity_before, quantity_after,
              reference_type, reference_id, notes, created_by)
             VALUES (?,?,?,?,?,?,?,?,?,?)"
        )->execute([
            $itemId, $itemName, $type, $change, $before, $before + $change,
            $refType ?: null, $refId ?: null, $notes ?: null,
            $user['id'] ?? null,
        ]);
    }

    public function forItem(int $itemId, int $limit = 20): array
    {
        return $this->query(
            "SELECT sm.*, u.name AS by_name FROM stock_movements sm
             LEFT JOIN users u ON u.id = sm.created_by
             WHERE sm.item_id = ?
             ORDER BY sm.created_at DESC LIMIT ?",
            [$itemId, $limit]
        )->fetchAll();
    }

    public function recent(int $limit = 15): array
    {
        return $this->query(
            "SELECT sm.*, u.name AS by_name FROM stock_movements sm
             LEFT JOIN users u ON u.id = sm.created_by
             ORDER BY sm.created_at DESC LIMIT ?",
            [$limit]
        )->fetchAll();
    }
}
