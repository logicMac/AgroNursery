<?php

namespace App\Models;

use App\Core\Model;

class AuditLog extends Model
{
    public function log(?int $userId, string $action, ?string $entityType, ?int $entityId, ?array $details): void
    {
        $this->insert('audit_logs', [
            'user_id' => $userId,
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'details' => $details ? json_encode($details) : null,
        ]);
    }

    public function recent(int $limit = 50): array
    {
        return $this->fetchAll('SELECT a.*, u.name as user_name FROM audit_logs a LEFT JOIN users u ON a.user_id = u.id ORDER BY a.created_at DESC LIMIT ?', [$limit]);
    }
}
