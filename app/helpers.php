<?php

function e(?string $text): string {
    return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
}

function old(array $data, string $key, $default = ''): string {
    return e($data[$key] ?? $default);
}

function asset(string $path): string {
    return BASE_URL . '/assets/' . ltrim($path, '/');
}

function url(string $path = ''): string {
    return BASE_URL . '/' . ltrim($path, '/');
}

function flash(string $type, string $message): void {
    $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
}

function renderFlash(): string {
    if (empty($_SESSION['flash'])) {
        return '';
    }
    $out = '';
    foreach ($_SESSION['flash'] as $f) {
        $color = $f['type'] === 'success' ? 'bg-green-100 text-green-800 border-green-300' : 'bg-red-100 text-red-800 border-red-300';
        $out .= '<div class="' . $color . ' border rounded-lg px-4 py-3 mb-4">' . e($f['message']) . '</div>';
    }
    $_SESSION['flash'] = [];
    return $out;
}
