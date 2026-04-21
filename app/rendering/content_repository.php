<?php

declare(strict_types=1);

function decode_json_column(mixed $value): array
{
    if (!is_string($value) || $value === '') {
        return [];
    }

    $decoded = json_decode($value, true);

    return is_array($decoded) ? $decoded : [];
}

function enrich_block_data(PDO $pdo, array $content, array $block): array
{
    $moduleKey = (string) ($block['module_key'] ?? '');

    if ($moduleKey === 'related_content') {
        $binding = $block['binding_json'] ?? [];
        $relationType = (string) ($binding['relation_type'] ?? 'related');
        $limit = (int) ($binding['limit'] ?? 3);

        $statement = $pdo->prepare(
            'SELECT
                target.id,
                target.title,
                target.slug,
                target.summary,
                target.route_mode,
                target.custom_path,
                target.seo_json
            FROM content_relations cr
            INNER JOIN content_items target ON target.id = cr.target_content_id
            WHERE cr.source_content_id = :source_content_id
              AND cr.relation_type = :relation_type
              AND target.status = :status
            ORDER BY cr.sort_order ASC, cr.id ASC
            LIMIT ' . $limit
        );

        $statement->execute([
            'source_content_id' => $content['id'],
            'relation_type' => $relationType,
            'status' => 'published',
        ]);

        $items = [];

        foreach ($statement->fetchAll() as $item) {
            $item['seo_json'] = decode_json_column($item['seo_json'] ?? null);
            $item['canonical_path'] = build_content_canonical_path($item);
            $items[] = $item;
        }

        $block['resolved_items'] = $items;
    }

    return $block;
}

function fetch_content_by_slug(PDO $pdo, string $slug): ?array
{
    return fetch_content_by_route($pdo, null, $slug, null);
}

function fetch_content_by_route(PDO $pdo, ?string $routeMode, ?string $slug, ?string $customPath): ?array
{
    $statement = $pdo->prepare(
        'SELECT
            ci.*,
            ct.type_key,
            t.template_key,
            s.skin_key
        FROM content_items ci
        INNER JOIN content_types ct ON ct.id = ci.content_type_id
        LEFT JOIN templates t ON t.id = ci.template_id
        LEFT JOIN skins s ON s.id = ci.skin_id
        WHERE ci.status = :status
          AND (
                (:route_mode IS NOT NULL AND ci.route_mode = :route_mode AND (
                    (:slug IS NOT NULL AND ci.slug = :slug)
                    OR (:custom_path IS NOT NULL AND ci.custom_path = :custom_path)
                ))
                OR (:route_mode IS NULL AND :slug IS NOT NULL AND ci.slug = :slug)
          )
        LIMIT 1'
    );

    $statement->execute([
        'route_mode' => $routeMode,
        'slug' => $slug,
        'custom_path' => $customPath,
        'status' => 'published',
    ]);

    $content = $statement->fetch();

    if (!is_array($content)) {
        return null;
    }

    $content['content_json'] = decode_json_column($content['content_json'] ?? null);
    $content['seo_json'] = decode_json_column($content['seo_json'] ?? null);
    $content['template'] = [
        'key' => $content['template_key'] ?? null,
        'structure' => [],
        'slots' => [],
        'css_config' => [],
    ];
    $content['skin'] = [
        'key' => $content['skin_key'] ?? null,
        'tokens' => [],
        'typography' => [],
        'css_config' => [],
    ];

    if (!empty($content['template_id'])) {
        $templateStatement = $pdo->prepare(
            'SELECT structure_json, slot_schema_json, css_config_json
            FROM templates
            WHERE id = :id
            LIMIT 1'
        );
        $templateStatement->execute([
            'id' => $content['template_id'],
        ]);
        $template = $templateStatement->fetch();

        if (is_array($template)) {
            $content['template']['structure'] = decode_json_column($template['structure_json'] ?? null);
            $content['template']['slots'] = decode_json_column($template['slot_schema_json'] ?? null);
            $content['template']['css_config'] = decode_json_column($template['css_config_json'] ?? null);
        }
    }

    if (!empty($content['skin_id'])) {
        $skinStatement = $pdo->prepare(
            'SELECT token_json, typography_json, css_config_json
            FROM skins
            WHERE id = :id
            LIMIT 1'
        );
        $skinStatement->execute([
            'id' => $content['skin_id'],
        ]);
        $skin = $skinStatement->fetch();

        if (is_array($skin)) {
            $content['skin']['tokens'] = decode_json_column($skin['token_json'] ?? null);
            $content['skin']['typography'] = decode_json_column($skin['typography_json'] ?? null);
            $content['skin']['css_config'] = decode_json_column($skin['css_config_json'] ?? null);
        }
    }

    $blocksStatement = $pdo->prepare(
        'SELECT
            cb.*,
            md.module_key,
            md.module_kind
        FROM content_blocks cb
        LEFT JOIN module_definitions md ON md.id = cb.module_definition_id
        WHERE cb.content_item_id = :content_item_id
        ORDER BY cb.sort_order ASC, cb.id ASC'
    );

    $blocksStatement->execute([
        'content_item_id' => $content['id'],
    ]);

    $blocks = [];

    foreach ($blocksStatement->fetchAll() as $block) {
        $block['layout_json'] = decode_json_column($block['layout_json'] ?? null);
        $block['style_json'] = decode_json_column($block['style_json'] ?? null);
        $block['content_json'] = decode_json_column($block['content_json'] ?? null);
        $block['data_contract_json'] = decode_json_column($block['data_contract_json'] ?? null);
        $block['binding_json'] = decode_json_column($block['binding_json'] ?? null);
        $block = enrich_block_data($pdo, $content, $block);
        $blocks[] = $block;
    }

    $content['blocks'] = $blocks;
    $content['blocks_by_slot'] = group_blocks_by_slot($blocks);
    $content['canonical_path'] = build_content_canonical_path($content);

    return $content;
}

function build_content_canonical_path(array $content): string
{
    return match ((string) ($content['route_mode'] ?? 'page_slug')) {
        'kr_world' => '/kr-world',
        'event_slug' => '/event?slug=' . urlencode((string) $content['slug']),
        'custom' => (string) ($content['custom_path'] ?? '/'),
        default => '/page?slug=' . urlencode((string) $content['slug']),
    };
}
