<?php

namespace App\Models;

use App\Core\Model;

class QualityAssessment extends Model
{
    public function all(): array
    {
        return $this->fetchAll('SELECT q.*, p.name as product_name, b.lot_id, u.name as staff_name FROM quality_assessments q JOIN products p ON q.product_id = p.id JOIN batches b ON q.batch_id = b.id LEFT JOIN users u ON q.assessed_by = u.id ORDER BY q.assessed_at DESC');
    }

    public function byBatch(int $batchId): array
    {
        return $this->fetchAll('SELECT q.*, u.name as staff_name FROM quality_assessments q LEFT JOIN users u ON q.assessed_by = u.id WHERE q.batch_id = ? ORDER BY q.assessed_at DESC', [$batchId]);
    }

    public function create(array $data): int
    {
        return $this->insert('quality_assessments', $data);
    }

    public function latestGrade(int $batchId): ?array
    {
        return $this->fetchOne('SELECT grade FROM quality_assessments WHERE batch_id = ? ORDER BY assessed_at DESC LIMIT 1', [$batchId]);
    }
}
