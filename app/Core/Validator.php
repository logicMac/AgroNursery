<?php

namespace App\Core;

class Validator
{
    private array $errors = [];

    public function required(array $data, array $keys): void
    {
        foreach ($keys as $key) {
            if (empty($data[$key]) && $data[$key] !== '0' && $data[$key] !== 0) {
                $this->errors[$key] = 'This field is required.';
            }
        }
    }

    public function email(string $value): void
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'] = 'Invalid email address.';
        }
    }

    public function minLength(string $value, int $min, string $key): void
    {
        if (strlen(trim($value)) < $min) {
            $this->errors[$key] = "Must be at least $min characters.";
        }
    }

    public function numeric(array $data, array $keys): void
    {
        foreach ($keys as $key) {
            if (isset($data[$key]) && !is_numeric($data[$key])) {
                $this->errors[$key] = 'Must be a number.';
            }
        }
    }

    public function fails(): bool
    {
        return !empty($this->errors);
    }

    public function errors(): array
    {
        return $this->errors;
    }
}
