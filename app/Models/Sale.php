<?php

namespace App\Models;

use App\Core\Model;

class Sale extends Model
{
    public function all(): array
    {
        return $this->fetchAll('SELECT s.*, u.name as seller_name FROM sales s JOIN users u ON s.sold_by = u.id ORDER BY s.sale_date DESC');
    }

    public function find(int $id): ?array
    {
        return $this->fetchOne('SELECT s.*, u.name as seller_name FROM sales s JOIN users u ON s.sold_by = u.id WHERE s.id = ?', [$id]);
    }

    public function create(array $data): int
    {
        return $this->insert('sales', $data);
    }

    public function updateSale(int $id, array $data): int
    {
        return $this->update('sales', $data, 'id = :id', ['id' => $id]);
    }

    public function nextReceiptNumber(): string
    {
        $year = date('Y');
        $prefix = 'RCP-' . $year . '-';
        $row = $this->fetchOne("SELECT receipt_number FROM sales WHERE receipt_number LIKE ? ORDER BY id DESC LIMIT 1", [$prefix . '%']);
        $num = 1;
        if ($row) {
            $parts = explode('-', $row['receipt_number']);
            $last = (int) end($parts);
            $num = $last + 1;
        }
        return $prefix . str_pad((string) $num, 4, '0', STR_PAD_LEFT);
    }

    public function items(int $saleId): array
    {
        return $this->fetchAll('SELECT si.*, p.name as product_name, b.lot_id FROM sale_items si LEFT JOIN products p ON si.product_id = p.id LEFT JOIN batches b ON si.batch_id = b.id WHERE si.sale_id = ?', [$saleId]);
    }

    public function revenueByDay(int $days = 30): array
    {
        return $this->fetchAll('SELECT DATE(sale_date) as day, SUM(total_amount) as total FROM sales WHERE status = "paid" AND sale_date >= DATE_SUB(CURDATE(), INTERVAL ? DAY) GROUP BY DATE(sale_date) ORDER BY day', [$days]);
    }

    public function topProducts(int $limit = 5): array
    {
        return $this->fetchAll('SELECT p.name, SUM(si.quantity) as qty, SUM(si.quantity * si.unit_price) as revenue FROM sale_items si JOIN products p ON si.product_id = p.id GROUP BY p.id ORDER BY qty DESC LIMIT ?', [$limit]);
    }

    public function gradeVsSales(): array
    {
        return $this->fetchAll('SELECT si.quality_grade, SUM(si.quantity) as sold, AVG(si.unit_price) as avg_price FROM sale_items si WHERE si.quality_grade IS NOT NULL GROUP BY si.quality_grade');
    }

    public function createItem(array $data): int
    {
        return $this->insert('sale_items', $data);
    }
}
