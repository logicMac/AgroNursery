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
        $photoPath = null;
        if (!empty($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
            $photoPath = $this->uploadPhoto($_FILES['profile_photo']);
            if ($photoPath === false) {
                flash('error', 'Invalid image. Only JPG, PNG, GIF up to 2MB.');
                $this->redirect('/users/create');
            }
        }

        $id = (new User())->create([
            'role_id' => $_POST['role_id'],
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'password_hash' => password_hash($_POST['password'], PASSWORD_DEFAULT),
            'profile_photo' => $photoPath,
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

    public function profile(): void
    {
        $this->requireAuth();
        $user = (new User())->find($this->userId());
        $this->view('users/profile', ['user' => $user, 'title' => 'My Profile']);
    }

    public function updateProfile(): void
    {
        $this->requireAuth();
        if (!Csrf::verify($_POST['csrf_token'] ?? null)) {
            flash('error', 'Invalid request.');
            $this->redirect('/profile');
        }

        $userId = $this->userId();
        $user = (new User())->find($userId);

        $data = [];
        if (!empty($_POST['name'])) {
            $data['name'] = $_POST['name'];
        }
        if (!empty($_POST['password'])) {
            $validator = new Validator();
            $validator->minLength($_POST['password'], 8, 'password');
            if ($validator->fails()) {
                flash('error', 'Password must be at least 8 characters.');
                $this->redirect('/profile');
            }
            $data['password_hash'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
        }

        if (!empty($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
            $photoPath = $this->uploadPhoto($_FILES['profile_photo']);
            if ($photoPath === false) {
                flash('error', 'Invalid image. Only JPG, PNG, GIF up to 2MB.');
                $this->redirect('/profile');
            }
            // Delete old photo
            if (!empty($user['profile_photo'])) {
                $oldPath = __DIR__ . '/../../public/' . ltrim($user['profile_photo'], '/');
                if (is_file($oldPath)) {
                    unlink($oldPath);
                }
            }
            $data['profile_photo'] = $photoPath;
        }

        if (!empty($data)) {
            (new User())->updateUser($userId, $data);
            if (isset($data['name'])) {
                $_SESSION['user']['name'] = $data['name'];
            }
            if (isset($data['profile_photo'])) {
                $_SESSION['user']['profile_photo'] = $data['profile_photo'];
            }
            (new AuditLog())->log($userId, 'profile_updated', 'users', $userId, []);
        }

        flash('success', 'Profile updated.');
        $this->redirect('/profile');
    }

    private function uploadPhoto(array $file): string|false
    {
        $allowed = ['image/jpeg', 'image/png', 'image/gif'];
        $maxSize = 2 * 1024 * 1024; // 2MB

        if (!in_array($file['type'], $allowed) || $file['size'] > $maxSize) {
            return false;
        }

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('user_') . '.' . strtolower($ext);
        $uploadDir = __DIR__ . '/../../public/uploads/users/';
        $dest = $uploadDir . $filename;

        if (!move_uploaded_file($file['tmp_name'], $dest)) {
            return false;
        }

        return 'uploads/users/' . $filename;
    }
}
