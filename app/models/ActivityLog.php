<?php

declare(strict_types=1);

namespace App\Models;

class ActivityLog extends BaseModel
{
    protected string $table = 'activity_logs';

    public static function log(
        string $action,
        string $entityType,
        ?int   $entityId   = null,
        string $entityName = '',
        string $details    = ''
    ): void {
        $db   = \App\Config\Database::getInstance();
        $user = auth();

        $stmt = $db->prepare(
            "INSERT INTO activity_logs
             (user_id, user_name, action, entity_type, entity_id, entity_name, details, ip_address)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $user['id']    ?? null,
            $user['name']  ?? 'System',
            $action,
            $entityType,
            $entityId,
            $entityName,
            $details,
            $_SERVER['REMOTE_ADDR'] ?? null,
        ]);
    }

    public function paginated(int $offset, int $limit): array
    {
        return $this->query(
            "SELECT * FROM activity_logs ORDER BY created_at DESC LIMIT ? OFFSET ?",
            [$limit, $offset]
        )->fetchAll();
    }

    public function recent(int $limit = 10): array
    {
        return $this->query(
            "SELECT * FROM activity_logs ORDER BY created_at DESC LIMIT ?",
            [$limit]
        )->fetchAll();
    }
}
