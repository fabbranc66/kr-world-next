<?php

declare(strict_types=1);

function normalize_request_path(array $request): string
{
    $uriPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    $path = is_string($uriPath) ? $uriPath : '/';
    $basePath = (string) ($request['base_path'] ?? '');

    if ($basePath !== '' && str_starts_with($path, $basePath)) {
        $path = substr($path, strlen($basePath)) ?: '/';
    }

    $path = '/' . trim($path, '/');

    return $path === '//' ? '/' : $path;
}

function current_query_string(): array
{
    $query = [];

    foreach ($_GET as $key => $value) {
        if (is_scalar($value)) {
            $query[(string) $key] = (string) $value;
        }
    }

    return $query;
}

function resolve_route(PDO $pdo, array $request): array
{
    $path = normalize_request_path($request);
    $query = current_query_string();

    if ($path === '/' || $path === '') {
        return [
            'type' => 'redirect',
            'status_code' => 302,
            'location' => ($request['base_path'] ?: '') . '/kr-world',
        ];
    }

    if ($path === '/kr-world') {
        $content = fetch_content_by_route($pdo, 'kr_world', null, '/kr-world');

        if ($content !== null) {
            return [
                'type' => 'content',
                'content' => $content,
            ];
        }
    }

    if ($path === '/sandbox') {
        $modelKey = !empty($query['model']) ? $query['model'] : 'homepage_lab';
        $versionNo = !empty($query['version']) ? (int) $query['version'] : null;
        $selectedSourceKey = !empty($query['data_source_key']) ? $query['data_source_key'] : null;

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'GET' && !empty($query['action']) && $query['action'] === 'create_model') {
            $createdModelKey = create_sandbox_model($pdo, $query);

            if ($createdModelKey !== null) {
                $modelKey = $createdModelKey;
                $versionNo = 1;
            }
        }

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'GET' && !empty($query['action']) && $query['action'] === 'update_state') {
            update_sandbox_version_state($pdo, $modelKey, $query);
        }

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'GET' && !empty($query['action']) && $query['action'] === 'select_binding') {
            update_sandbox_version_state($pdo, $modelKey, [
                'selected_slot' => $query['selected_slot'] ?? null,
                'selected_binding_id' => $query['binding_id'] ?? null,
            ]);
        }

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'GET' && !empty($query['action']) && $query['action'] === 'clone_version') {
            create_sandbox_version($pdo, $modelKey, $versionNo);
        }

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'GET' && !empty($query['action']) && $query['action'] === 'create_binding') {
            create_sandbox_binding($pdo, $modelKey, $query);
        }

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'GET' && !empty($query['action']) && $query['action'] === 'delete_binding') {
            delete_sandbox_binding($pdo, $modelKey, $query);
        }

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'GET' && !empty($query['action']) && $query['action'] === 'move_binding') {
            move_sandbox_binding($pdo, $modelKey, $query);
        }

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'GET' && !empty($query['action']) && $query['action'] === 'update_binding') {
            update_sandbox_binding_properties($pdo, $modelKey, $query);
        }

        $sandbox = fetch_sandbox_model_by_key($pdo, $modelKey, $versionNo, $selectedSourceKey);

        if ($sandbox !== null) {
            return [
                'type' => 'sandbox',
                'sandbox' => $sandbox,
            ];
        }

        $fallback = fetch_sandbox_model_by_key($pdo, 'homepage_lab', null, $selectedSourceKey);

        if ($fallback !== null) {
            return [
                'type' => 'sandbox',
                'sandbox' => $fallback,
            ];
        }
    }

    if ($path === '/page' && !empty($query['slug'])) {
        $content = fetch_content_by_route($pdo, 'page_slug', $query['slug'], null);

        if ($content !== null) {
            return [
                'type' => 'content',
                'content' => $content,
            ];
        }
    }

    if ($path === '/event' && !empty($query['slug'])) {
        $content = fetch_content_by_route($pdo, 'event_slug', $query['slug'], null);

        if ($content !== null) {
            return [
                'type' => 'content',
                'content' => $content,
            ];
        }
    }

    return [
        'type' => 'not_found',
        'status_code' => 404,
    ];
}
