<?php

namespace App\Models;

use App\Core\Model;

class StockMovement extends Model
{
    public function all(): array
    {
        return $this->fetchAll('SELECT sm.*, p.name as product_name, b.lot_id, u.name as staff_name FROM stock_movements sm JOIN products p ON sm.product_id = p.id LEFT JOIN batches b ON sm.batch_id = b.id LEFT JOIN users u ON sm.staff_id = u.id ORDER BY sm.created_at DESC');
    }

    public function byProduct(int $productId): array
    {
        return $this->fetchAll('SELECT sm.*, u.name as staff_name FROM stock_movements sm LEFT JOIN users u ON sm.staff_id = u.id WHERE sm.product_id = ? ORDER BY sm.created_at DESC', [$productId]);
    }

    public function create(array $data): int
    {
        return $this->insert('stock_movements', $data);
    }

    public function inOutByProduct(int $productId): ?array
    {
        return $this->fetchOne('SELECT COALESCE(SUM(CASE WHEN movement_type = "in" THEN quantity ELSE 0 END),0) as total_in, COALESCE(SUM(CASE WHEN movement_type IN ("out","damage") THEN quantity ELSE 0 END),0) as total_out FROM stock_movements WHERE product_id = ?', [$productId]);
    }
}
