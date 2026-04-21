<?php

declare(strict_types=1);

function env_value(string $key, mixed $default = null): mixed
{
    return $_ENV[$key] ?? $_SERVER[$key] ?? $default;
}

function current_request_context(array $config): array
{
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $scriptName = $_SERVER['SCRIPT_NAME'] ?? '/index.php';
    $basePath = trim((string) dirname($scriptName), '.\\/');
    $basePath = $basePath === '' ? '' : '/' . $basePath;

    if (str_ends_with($basePath, '/public')) {
        $basePath = substr($basePath, 0, -7);
        $basePath = $basePath === '' ? '' : $basePath;
    }

    if (!empty($config['app_base_path'])) {
        $basePath = '/' . trim((string) $config['app_base_path'], '/');
        $basePath = $basePath === '/' ? '' : $basePath;
    }

    return [
        'scheme' => $scheme,
        'host' => $host,
        'base_path' => $basePath,
        'is_local_host' => in_array(strtolower(preg_replace('/:\d+$/', '', $host)), ['localhost', '127.0.0.1'], true),
    ];
}

function build_environment_links(array $config, array $request): array
{
    $path = $request['base_path'] ?: '';
    $currentUrl = sprintf('%s://%s%s/', $request['scheme'], $request['host'], $path);
    $lanHost = trim((string) $config['lan_host']);
    $lanUrl = $lanHost !== '' ? sprintf('http://%s%s/', $lanHost, $path) : null;

    $publicUrl = trim((string) $config['app_url']);
    if ($publicUrl === '') {
        $publicUrl = $request['is_local_host'] && $lanUrl !== null ? $lanUrl : $currentUrl;
    }

    return [
        'current_url' => $currentUrl,
        'lan_url' => $lanUrl,
        'public_url' => rtrim($publicUrl, '/') . '/',
        'qr_url' => rtrim($publicUrl, '/') . '/',
    ];
}
