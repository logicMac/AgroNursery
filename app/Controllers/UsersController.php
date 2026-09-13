<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Validator;
use App\Models\User;
use App\Models\Role;
use App\Models\AuditLog;

class UsersController extends Controller
{
    public function index(): void
    {
        $this->requirePermission('users');
        $this->view('users/index', [
            'users' => (new User())->all(),
            'roles' => (new Role())->all(),
            'title' => 'Users'
        ]);
    }

    public function create(): void
    {
        $this->requirePermission('users');
        $this->view('users/create', ['roles' => (new Role())->all(), 'title' => 'New User']);
    }

    public function store(): void
    {
        $this->requirePermission('users');
        if (!Csrf::verify($_POST['csrf_token'] ?? null)) {
            flash('error', 'Invalid request.');
            $this->redirect('/users/create');
        }
        $validator = new Validator();
        $validator->required($_POST, ['name', 'email', 'role_id', 'password']);
        $validator->email($_POST['email']);
        $validator->minLength($_POST['password'], 8, 'password');
        if ($validator->fails()) {
            flash('error', 'Validation failed: ' . implode(' ', $validator->errors()));
            $this->redirect('/users/create');
        }
        $id = (new User())->create([
            'role_id' => $_POST['role_id'],
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'password_hash' => password_hash($_POST['password'], PASSWORD_DEFAULT),
        ]);
        (new AuditLog())->log($this->userId(), 'user_created', 'users', $id, ['name' => $_POST['name']]);
        flash('success', 'User created.');
        $this->redirect('/users');
    }

    public function toggle(int $id): void
    {
        $this->requirePermission('users');
        if (!Csrf::verify($_POST['csrf_token'] ?? null)) {
            flash('error', 'Invalid request.');
            $this->redirect('/users');
        }
        $user = (new User())->find($id);
        (new User())->updateUser($id, ['is_active' => $user['is_active'] ? 0 : 1]);
        (new AuditLog())->log($this->userId(), 'user_toggled', 'users', $id, ['active' => !$user['is_active']]);
        flash('success', 'User status updated.');
        $this->redirect('/users');
    }
}
