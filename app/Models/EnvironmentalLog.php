<?php

namespace App\Models;

use App\Core\Model;

class EnvironmentalLog extends Model
{
    public function all(): array
    {
        return $this->fetchAll('SELECT e.*, p.name as product_name, b.lot_id, u.name as staff_name FROM environmental_logs e LEFT JOIN batches b ON e.batch_id = b.id LEFT JOIN products p ON b.product_id = p.id LEFT JOIN users u ON e.staff_id = u.id ORDER BY e.log_date DESC, e.created_at DESC');
    }

    public function byBatch(int $batchId): array
    {
        return $this->fetchAll('SELECT e.*, u.name as staff_name FROM environmental_logs e LEFT JOIN users u ON e.staff_id = u.id WHERE e.batch_id = ? ORDER BY e.log_date DESC', [$batchId]);
    }

    public function dueToday(int $staffId): array
    {
        return $this->fetchAll('SELECT e.*, b.lot_id, p.name as product_name FROM environmental_logs e JOIN batches b ON e.batch_id = b.id JOIN products p ON b.product_id = p.id WHERE e.log_date = CURDATE() AND (e.staff_id = ? OR e.staff_id IS NULL) ORDER BY e.activity', [$staffId]);
    }

    public function create(array $data): int
    {
        return $this->insert('environmental_logs', $data);
    }

    public function averagesByBatch(int $batchId): ?array
    {
        return $this->fetchOne('SELECT AVG(soil_ph) as avg_ph, AVG(temperature_c) as avg_temp, AVG(humidity_pct) as avg_humidity, AVG(water_ml) as avg_water FROM environmental_logs WHERE batch_id = ?', [$batchId]);
    }
}
