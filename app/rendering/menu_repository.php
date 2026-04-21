<?php

declare(strict_types=1);

function fetch_settings(PDO $pdo, string $scope = 'system'): array
{
    $statement = $pdo->prepare(
        'SELECT setting_key, setting_value
        FROM settings
        WHERE scope = :scope'
    );

    $statement->execute([
        'scope' => $scope,
    ]);

    $settings = [];

    foreach ($statement->fetchAll() as $row) {
        $settings[(string) $row['setting_key']] = decode_json_column($row['setting_value'] ?? null);
    }

    return $settings;
}

function fetch_menu_by_key(PDO $pdo, string $menuKey): ?array
{
    $statement = $pdo->prepare(
        'SELECT *
        FROM menus
        WHERE menu_key = :menu_key
          AND is_active = 1
        LIMIT 1'
    );

    $statement->execute([
        'menu_key' => $menuKey,
    ]);

    $menu = $statement->fetch();

    if (!is_array($menu)) {
        return null;
    }

    $itemsStatement = $pdo->prepare(
        'SELECT
            mi.*,
            ci.slug,
            ci.route_mode,
            ci.custom_path
        FROM menu_items mi
        LEFT JOIN content_items ci ON ci.id = mi.content_item_id
        WHERE mi.menu_id = :menu_id
          AND mi.is_active = 1
        ORDER BY mi.sort_order ASC, mi.id ASC'
    );

    $itemsStatement->execute([
        'menu_id' => $menu['id'],
    ]);

    $items = [];

    foreach ($itemsStatement->fetchAll() as $item) {
        $item['meta_json'] = decode_json_column($item['meta_json'] ?? null);
        $item['resolved_url'] = $item['url'] ?: build_content_canonical_path($item);
        $items[] = $item;
    }

    $menu['items'] = $items;

    return $menu;
}
