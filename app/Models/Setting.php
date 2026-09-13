<?php

namespace App\Models;

use App\Core\Model;

class Setting extends Model
{
    public function get(string $key, ?string $default = null): ?string
    {
        $row = $this->fetchOne('SELECT value FROM settings WHERE `key` = ?', [$key]);
        return $row === null ? $default : $row['value'];
    }

    public function set(string $key, string $value): void
    {
        $exists = $this->fetchOne('SELECT id FROM settings WHERE `key` = ?', [$key]);
        if ($exists) {
            $this->update('settings', ['value' => $value], '`key` = :whereKey', ['whereKey' => $key]);
        } else {
            $this->insert('settings', ['key' => $key, 'value' => $value]);
        }
    }

    public function all(): array
    {
        return $this->fetchAll('SELECT * FROM settings ORDER BY `key`');
    }

    public function getMany(array $keys): array
    {
        $placeholders = implode(',', array_fill(0, count($keys), '?'));
        $rows = $this->fetchAll("SELECT `key`, value FROM settings WHERE `key` IN ($placeholders)", $keys);
        $out = [];
        foreach ($rows as $row) {
            $out[$row['key']] = $row['value'];
        }
        return $out;
    }
}
