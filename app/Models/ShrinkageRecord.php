<?php

namespace App\Models;

use App\Core\Model;

class ShrinkageRecord extends Model
{
    public function all(): array
    {
        return $this->fetchAll('SELECT sr.*, p.name as product_name, b.lot_id, u.name as staff_name FROM shrinkage_records sr JOIN products p ON sr.product_id = p.id LEFT JOIN batches b ON sr.batch_id = b.id LEFT JOIN users u ON sr.recorded_by = u.id ORDER BY sr.recorded_at DESC');
    }

    public function byBatch(int $batchId): array
    {
        return $this->fetchAll('SELECT * FROM shrinkage_records WHERE batch_id = ? ORDER BY recorded_at DESC', [$batchId]);
    }

    public function create(array $data): int
    {
        return $this->insert('shrinkage_records', $data);
    }

    public function totalsByCause(): array
    {
        return $this->fetchAll('SELECT cause_category, SUM(quantity) as total, COUNT(*) as records FROM shrinkage_records WHERE cause_category IS NOT NULL GROUP BY cause_category ORDER BY total DESC');
    }

    public function shrinkageByBatch(): array
    {
        return $this->fetchAll('SELECT b.lot_id, p.name as product_name, b.seedling_count, COALESCE(SUM(sr.quantity),0) as lost, ROUND(COALESCE(SUM(sr.quantity),0) / b.seedling_count * 100, 2) as rate FROM batches b JOIN products p ON b.product_id = p.id LEFT JOIN shrinkage_records sr ON sr.batch_id = b.id GROUP BY b.id HAVING b.seedling_count > 0 ORDER BY rate DESC');
    }
}
