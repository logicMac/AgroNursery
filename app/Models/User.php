<?php

namespace App\Models;

use App\Core\Model;

class User extends Model
{
    public function find(int $id): ?array
    {
        return $this->fetchOne('SELECT u.*, r.name as role_name, r.permissions FROM users u JOIN roles r ON u.role_id = r.id WHERE u.id = ?', [$id]);
    }

    public function findByEmail(string $email): ?array
    {
        return $this->fetchOne('SELECT * FROM users WHERE email = ?', [$email]);
    }

    public function all(): array
    {
        return $this->fetchAll('SELECT u.id, u.name, u.email, u.is_active, u.last_login, u.profile_photo, r.name as role_name FROM users u JOIN roles r ON u.role_id = r.id ORDER BY u.name');
    }

    public function create(array $data): int
    {
        return $this->insert('users', $data);
    }

    public function updateUser(int $id, array $data): int
    {
        return $this->update('users', $data, 'id = :id', ['id' => $id]);
    }

    public function recordFailedLogin(int $id): void
    {
        $this->query('UPDATE users SET login_attempts = login_attempts + 1 WHERE id = ?', [$id]);
    }

    public function clearLoginAttempts(int $id): void
    {
        $this->query('UPDATE users SET login_attempts = 0, locked_until = NULL WHERE id = ?', [$id]);
    }

    public function lock(int $id, string $until): void
    {
        $this->query('UPDATE users SET locked_until = ? WHERE id = ?', [$until, $id]);
    }

    public function updateLastLogin(int $id): void
    {
        $this->query('UPDATE users SET last_login = NOW() WHERE id = ?', [$id]);
    }
}
