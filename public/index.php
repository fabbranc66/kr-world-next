<?php

declare(strict_types=1);

$app = require dirname(__DIR__) . '/app/core/bootstrap.php';
$route = resolve_route($app['db'], $app['request']);

if ($route['type'] === 'redirect') {
    header('Location: ' . $route['location'], true, $route['status_code']);
    exit;
}

if ($route['type'] === 'not_found') {
    http_response_code($route['status_code']);
    exit('404');
}

if ($route['type'] === 'sandbox') {
    $sandbox = $route['sandbox'];
    require dirname(__DIR__) . '/themes/templates/sandbox.php';
    exit;
}

$page = $route['content'];

require dirname(__DIR__) . '/themes/templates/base.php';
