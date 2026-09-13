<?php

namespace App\Models;

use App\Core\Model;

class Forecast extends Model
{
    public function all(): array
    {
        return $this->fetchAll('SELECT f.*, b.lot_id, p.name as product_name FROM forecasts f JOIN batches b ON f.batch_id = b.id JOIN products p ON b.product_id = p.id ORDER BY f.created_at DESC');
    }

    public function byBatch(int $batchId): array
    {
        return $this->fetchAll('SELECT f.*, b.lot_id, p.name as product_name FROM forecasts f JOIN batches b ON f.batch_id = b.id JOIN products p ON b.product_id = p.id WHERE f.batch_id = ? ORDER BY f.created_at DESC', [$batchId]);
    }

    public function create(array $data): int
    {
        return $this->insert('forecasts', $data);
    }

    public function latestForBatch(int $batchId): ?array
    {
        return $this->fetchOne('SELECT * FROM forecasts WHERE batch_id = ? ORDER BY created_at DESC LIMIT 1', [$batchId]);
    }
}
