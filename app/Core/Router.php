<?php

namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $path, string $handler): void
    {
        $this->add('GET', $path, $handler);
    }

    public function post(string $path, string $handler): void
    {
        $this->add('POST', $path, $handler);
    }

    private function add(string $method, string $path, string $handler): void
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $this->normalize($path),
            'handler' => $handler,
        ];
    }

    private function normalize(string $path): string
    {
        $path = trim($path, '/');
        return preg_replace('#/{\w+}#', '/([^/]+)', $path) . '$';
    }

    public function resolve(string $method, string $uri): void
    {
        $uri = trim(parse_url($uri, PHP_URL_PATH), '/');
        $base = trim(BASE_URL, '/');
        $baseParts = explode('/', $base);
        $uriParts = explode('/', $uri);
        $uriParts = array_values(array_filter($uriParts, fn($p, $i) => !isset($baseParts[$i]) || $baseParts[$i] !== $p, ARRAY_FILTER_USE_BOTH));
        $uri = implode('/', $uriParts);

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }
            $pattern = '#^' . $route['path'] . '$#';
            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches);
                $this->dispatch($route['handler'], $matches);
                return;
            }
        }

        http_response_code(404);
        echo '404 Not Found';
    }

    private function dispatch(string $handler, array $params): void
    {
        [$class, $method] = explode('@', $handler);
        $class = 'App\\Controllers\\' . $class;
        if (!class_exists($class) || !method_exists($class, $method)) {
            http_response_code(500);
            echo 'Handler not found';
            return;
        }
        $controller = new $class();
        $controller->$method(...$params);
    }
}
