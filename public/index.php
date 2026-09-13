<?php

define('ROOT', dirname(__DIR__) . DIRECTORY_SEPARATOR);

require_once ROOT . 'config/config.php';
require_once ROOT . 'app/helpers.php';

spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $baseDir = ROOT . 'app' . DIRECTORY_SEPARATOR;
    $len = strlen($prefix);
    if (strncmp($class, $prefix, $len) !== 0) {
        return;
    }
    $relative = str_replace('\\', DIRECTORY_SEPARATOR, substr($class, $len));
    $file = $baseDir . $relative . '.php';
    if (is_file($file)) {
        require_once $file;
    }
});

use App\Core\Session;
use App\Core\Router;

Session::start();
Session::activityCheck();

$router = new Router();

require_once ROOT . 'routes.php';

$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'] ?? '/';

$router->resolve($method, $uri);
