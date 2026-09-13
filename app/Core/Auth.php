<?php

namespace App\Core;

use App\Models\User;

class Auth
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function attempt(string $email, string $password): bool
    {
        $user = $this->userModel->findByEmail($email);
        if (!$user) {
            return false;
        }
        if ($user['is_active'] !== 1) {
            return false;
        }
        if (!password_verify($password, $user['password_hash'])) {
            return false;
        }
        $this->login($user);
        return true;
    }

    private function login(array $user): void
    {
        Session::regenerate();
        $role = (new \App\Models\Role())->find($user['role_id']);
        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $role['name'] ?? 'staff',
            'profile_photo' => $user['profile_photo'] ?? null,
            'permissions' => json_decode($role['permissions'] ?? '{}', true),
        ];
        $this->userModel->updateLastLogin($user['id']);
    }
}
