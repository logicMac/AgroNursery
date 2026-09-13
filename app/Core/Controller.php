<?php

namespace App\Core;

class Controller
{
    protected function view(string $view, array $data = []): void
    {
        $file = __DIR__ . '/../Views/' . $view . '.php';
        if (!is_file($file)) {
            throw new \Exception("View not found: $view");
        }
        extract($data, EXTR_SKIP);
        require $file;
    }

    protected function json(array $data, int $code = 200): void
    {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function redirect(string $path): void
    {
        header('Location: ' . BASE_URL . '/' . ltrim($path, '/'));
        exit;
    }

    protected function session(): array
    {
        return $_SESSION['user'] ?? [];
    }

    protected function userId(): ?int
    {
        return $_SESSION['user']['id'] ?? null;
    }

    protected function requireAuth(): void
    {
        if (empty($_SESSION['user'])) {
            $this->redirect('/login');
        }
    }

    protected function requirePermission(string $key, ?string $action = null): void
    {
        $this->requireAuth();
        $perms = $_SESSION['user']['permissions'] ?? [];
        $value = $perms[$key] ?? false;
        $allowed = false;
        if ($value === true) {
            $allowed = true;
        } elseif (is_array($value) && in_array($action, $value, true)) {
            $allowed = true;
        }
        if (!$allowed) {
            http_response_code(403);
            $this->view('dashboard/forbidden', ['message' => 'Access denied.']);
            exit;
        }
    }
}
