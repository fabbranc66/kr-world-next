<?php

declare(strict_types=1);

require_once __DIR__ . '/env.php';
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/router.php';
require_once dirname(__DIR__) . '/rendering/content_repository.php';
require_once dirname(__DIR__) . '/rendering/menu_repository.php';
require_once dirname(__DIR__) . '/rendering/sandbox_repository.php';
require_once dirname(__DIR__) . '/rendering/template_renderer.php';

load_env(dirname(__DIR__, 2) . '/.env');

$config = require dirname(__DIR__, 2) . '/config/app.php';
$request = current_request_context($config);
$links = build_environment_links($config, $request);
$db = database_connection($config);
$systemSettings = fetch_settings($db, 'system');
$publicSettings = fetch_settings($db, 'public');
$menuKey = (string) ($publicSettings['header_menu_key']['value'] ?? 'main_header');
$headerMenu = fetch_menu_by_key($db, $menuKey);

return [
    'config' => $config,
    'request' => $request,
    'links' => $links,
    'db' => $db,
    'settings' => [
        'system' => $systemSettings,
        'public' => $publicSettings,
    ],
    'menus' => [
        'header' => $headerMenu,
    ],
];
