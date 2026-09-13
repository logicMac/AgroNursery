<?php

namespace App\Models;

use App\Core\Model;

class Product extends Model
{
    public function all(): array
    {
        return $this->fetchAll('SELECT * FROM products ORDER BY name');
    }

    public function find(int $id): ?array
    {
        return $this->fetchOne('SELECT * FROM products WHERE id = ?', [$id]);
    }

    public function create(array $data): int
    {
        return $this->insert('products', $data);
    }

    public function updateProduct(int $id, array $data): int
    {
        return $this->update('products', $data, 'id = :id', ['id' => $id]);
    }

    public function updateStock(int $id, int $delta): void
    {
        $this->query('UPDATE products SET current_stock = current_stock + ? WHERE id = ?', [$delta, $id]);
    }

    public function lowStock(): array
    {
        return $this->fetchAll('SELECT * FROM products WHERE current_stock <= min_stock AND is_active = 1');
    }
}
