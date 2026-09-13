<?php

namespace App\Models;

use App\Core\Model;

class Traceability extends Model
{
    public function chain(int $batchId): array
    {
        $batch = (new Batch())->find($batchId);
        $logs = (new EnvironmentalLog())->byBatch($batchId);
        $quality = (new QualityAssessment())->byBatch($batchId);
        $shrinkage = (new ShrinkageRecord())->byBatch($batchId);
        $sales = $this->fetchAll('SELECT s.receipt_number, s.sale_date, si.quantity, si.unit_price, si.quality_grade, u.name as buyer_staff FROM sales s JOIN sale_items si ON s.id = si.sale_id LEFT JOIN users u ON s.sold_by = u.id WHERE si.batch_id = ? ORDER BY s.sale_date', [$batchId]);

        return [
            'batch' => $batch,
            'environmental' => $logs,
            'quality' => $quality,
            'shrinkage' => $shrinkage,
            'sales' => $sales,
        ];
    }

    public function findByLot(string $lotId): ?array
    {
        $batch = (new Batch())->findByLot($lotId);
        if (!$batch) {
            return null;
        }
        return $this->chain($batch['id']);
    }
}
