<?php

namespace App\Models;

use App\Core\Model;

class Batch extends Model
{
    public function all(): array
    {
        return $this->fetchAll('SELECT b.*, p.name as product_name, p.image as product_image, u.name as staff_name FROM batches b JOIN products p ON b.product_id = p.id LEFT JOIN users u ON b.staff_id = u.id ORDER BY b.id DESC');
    }

    public function find(int $id): ?array
    {
        return $this->fetchOne('SELECT b.*, p.name as product_name, u.name as staff_name FROM batches b JOIN products p ON b.product_id = p.id LEFT JOIN users u ON b.staff_id = u.id WHERE b.id = ?', [$id]);
    }

    public function findByLot(string $lotId): ?array
    {
        return $this->fetchOne('SELECT * FROM batches WHERE lot_id = ?', [$lotId]);
    }

    public function create(array $data): int
    {
        return $this->insert('batches', $data);
    }

    public function updateBatch(int $id, array $data): int
    {
        return $this->update('batches', $data, 'id = :id', ['id' => $id]);
    }

    public function byProduct(int $productId): array
    {
        return $this->fetchAll('SELECT * FROM batches WHERE product_id = ? ORDER BY plant_date DESC', [$productId]);
    }

    public function readySoon(int $days = 14): array
    {
        return $this->fetchAll('SELECT b.*, p.name as product_name FROM batches b JOIN products p ON b.product_id = p.id WHERE b.expected_ready_date IS NOT NULL AND b.expected_ready_date <= DATE_ADD(CURDATE(), INTERVAL ? DAY) ORDER BY b.expected_ready_date ASC', [$days]);
    }
}
