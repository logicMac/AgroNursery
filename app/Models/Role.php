<?php

namespace App\Models;

use App\Core\Model;

class Role extends Model
{
    public function find(int $id): ?array
    {
        return $this->fetchOne('SELECT * FROM roles WHERE id = ?', [$id]);
    }

    public function all(): array
    {
        return $this->fetchAll('SELECT * FROM roles ORDER BY name');
    }

    public function getPermissions(int $id): array
    {
        $role = $this->find($id);
        return json_decode($role['permissions'] ?? '{}', true);
    }
}
