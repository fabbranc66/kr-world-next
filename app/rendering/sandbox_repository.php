<?php

declare(strict_types=1);

function sandbox_allowed_grid_modes(): array
{
    return ['snap_strong', 'snap_soft', 'snap_off'];
}

function sandbox_allowed_alignments(): array
{
    return ['start', 'center', 'end', 'stretch'];
}

function sandbox_allowed_measure_units(): array
{
    return ['px', 'percent'];
}

function sandbox_allowed_slot_layouts(): array
{
    return ['stack', 'grid', 'split'];
}

function sandbox_allowed_slot_visibility_modes(): array
{
    return ['visible', 'conditional', 'hidden'];
}

function sandbox_allowed_background_modes(): array
{
    return ['none', 'solid', 'gradient', 'image', 'gradient_image'];
}

function sandbox_allowed_background_sizes(): array
{
    return ['cover', 'contain', 'auto', '100% 100%'];
}

function sandbox_allowed_background_repeats(): array
{
    return ['no-repeat', 'repeat', 'repeat-x', 'repeat-y'];
}

function sandbox_allowed_background_attachments(): array
{
    return ['scroll', 'fixed', 'local'];
}

function sandbox_allowed_background_blends(): array
{
    return ['normal', 'multiply', 'screen', 'overlay', 'soft-light', 'hard-light'];
}

function sandbox_allowed_header_layouts(): array
{
    return ['split', 'stack', 'center'];
}

function sandbox_allowed_header_visibility_modes(): array
{
    return ['visible', 'hidden'];
}

function sandbox_allowed_header_navigation_modes(): array
{
    return ['show', 'hide'];
}

function sandbox_allowed_media_fit_modes(): array
{
    return ['cover', 'contain', 'fill'];
}

function sandbox_allowed_logo_scale_modes(): array
{
    return ['contain', 'cover', 'fill'];
}

function sandbox_allowed_media_ratios(): array
{
    return ['auto', '21:9', '16:9', '4:3', '1:1', '3:4', '9:16'];
}

function sandbox_default_body_background_settings(): array
{
    return [
        'body_background_mode' => 'none',
        'body_background_color' => '#101010',
        'body_background_gradient_from' => '#161616',
        'body_background_gradient_to' => '#090909',
        'body_background_gradient_angle' => 135,
        'body_background_image_url' => '',
        'body_background_position' => 'center center',
        'body_background_size' => 'cover',
        'body_background_repeat' => 'no-repeat',
        'body_background_attachment' => 'scroll',
        'body_background_blend_mode' => 'normal',
    ];
}

function sandbox_default_page_canvas_settings(): array
{
    return [
        'page_max_width_value' => 100,
        'page_max_width_unit' => 'percent',
        'page_padding_value' => 18,
        'page_padding_unit' => 'px',
        'page_slot_gap_value' => 12,
        'page_slot_gap_unit' => 'px',
        'page_background_mode' => 'none',
        'page_background_color' => '#121212',
        'page_background_gradient_from' => '#1a1a1a',
        'page_background_gradient_to' => '#0d0d0d',
        'page_background_gradient_angle' => 180,
        'page_background_image_url' => '',
        'page_background_position' => 'center center',
        'page_background_size' => 'cover',
        'page_background_repeat' => 'no-repeat',
        'page_background_attachment' => 'scroll',
        'page_background_blend_mode' => 'normal',
    ];
}

function sandbox_default_header_settings(): array
{
    return [
        'header_brand_label' => '',
        'header_logo_url' => '',
        'header_logo_width_value' => null,
        'header_logo_width_unit' => 'px',
        'header_logo_height_value' => null,
        'header_logo_height_unit' => 'px',
        'header_logo_max_height_value' => null,
        'header_logo_max_height_unit' => 'px',
        'header_logo_scale_mode' => 'contain',
        'header_visibility_mode' => 'visible',
        'header_navigation_mode' => 'show',
        'header_layout_mode' => 'split',
        'header_padding_value' => 12,
        'header_padding_unit' => 'px',
        'header_height_value' => 64,
        'header_height_unit' => 'px',
        'header_gap_value' => 16,
        'header_gap_unit' => 'px',
        'header_background_mode' => 'none',
        'header_background_color' => '#090909',
        'header_background_gradient_from' => '#121212',
        'header_background_gradient_to' => '#090909',
        'header_background_gradient_angle' => 180,
        'header_background_image_url' => '',
        'header_background_position' => 'center center',
        'header_background_size' => 'cover',
        'header_background_repeat' => 'no-repeat',
        'header_background_attachment' => 'scroll',
        'header_background_blend_mode' => 'normal',
    ];
}

function sandbox_default_footer_settings(): array
{
    return [
        'footer_label' => 'Footer',
        'footer_visibility_mode' => 'visible',
        'footer_navigation_mode' => 'show',
        'footer_layout_mode' => 'split',
        'footer_padding_value' => 12,
        'footer_padding_unit' => 'px',
        'footer_height_value' => 72,
        'footer_height_unit' => 'px',
        'footer_gap_value' => 16,
        'footer_gap_unit' => 'px',
        'footer_background_mode' => 'none',
        'footer_background_color' => '#090909',
        'footer_background_gradient_from' => '#121212',
        'footer_background_gradient_to' => '#090909',
        'footer_background_gradient_angle' => 180,
        'footer_background_image_url' => '',
        'footer_background_position' => 'center center',
        'footer_background_size' => 'cover',
        'footer_background_repeat' => 'no-repeat',
        'footer_background_attachment' => 'scroll',
        'footer_background_blend_mode' => 'normal',
    ];
}

function sandbox_media_library_map(): array
{
    return [
        'header_logo' => [
            'directory' => dirname(__DIR__, 2) . '/public/assets/media/logos',
            'public_path' => '/assets/media/logos',
            'allowed_extensions' => ['png', 'jpg', 'jpeg', 'webp', 'gif', 'svg'],
        ],
        'navigation' => [
            'directory' => dirname(__DIR__, 2) . '/public/assets/media/navigation',
            'public_path' => '/assets/media/navigation',
            'allowed_extensions' => ['png', 'jpg', 'jpeg', 'webp', 'gif', 'svg'],
        ],
        'footer_navigation' => [
            'directory' => dirname(__DIR__, 2) . '/public/assets/media/footer-navigation',
            'public_path' => '/assets/media/footer-navigation',
            'allowed_extensions' => ['png', 'jpg', 'jpeg', 'webp', 'gif', 'svg'],
        ],
        'binding_media' => [
            'directory' => dirname(__DIR__, 2) . '/public/assets/media/components',
            'public_path' => '/assets/media/components',
            'allowed_extensions' => ['png', 'jpg', 'jpeg', 'webp', 'gif', 'svg', 'mp4', 'webm'],
        ],
    ];
}

function sandbox_media_library_config(string $context): ?array
{
    $map = sandbox_media_library_map();

    return $map[$context] ?? null;
}

function fetch_sandbox_media_library(string $context, string $basePath = ''): array
{
    $config = sandbox_media_library_config($context);
    if ($config === null) {
        return [];
    }

    $directory = $config['directory'];
    if (!is_dir($directory)) {
        return [];
    }

    $items = [];
    $entries = scandir($directory) ?: [];

    foreach ($entries as $entry) {
        if ($entry === '.' || $entry === '..') {
            continue;
        }

        $absolutePath = $directory . '/' . $entry;
        if (!is_file($absolutePath)) {
            continue;
        }

        $extension = strtolower((string) pathinfo($absolutePath, PATHINFO_EXTENSION));
        if (!in_array($extension, $config['allowed_extensions'], true)) {
            continue;
        }

        $items[] = [
            'name' => $entry,
            'url' => rtrim($basePath, '/') . $config['public_path'] . '/' . rawurlencode($entry),
            'modified_at' => filemtime($absolutePath) ?: 0,
        ];
    }

    usort($items, static fn (array $left, array $right): int => ($right['modified_at'] <=> $left['modified_at']) ?: strcmp($left['name'], $right['name']));

    return $items;
}

function fetch_sandbox_logo_library(string $basePath = ''): array
{
    return fetch_sandbox_media_library('header_logo', $basePath);
}

function sandbox_normalize_style_profile(array $style): array
{
    $defaults = [
        'skin_level' => 'editorial_dark',
        'grid' => 'enabled',
    ] + sandbox_default_body_background_settings() + sandbox_default_page_canvas_settings() + sandbox_default_header_settings() + sandbox_default_footer_settings();

    $style = array_merge($defaults, $style);

    if (!in_array((string) $style['body_background_mode'], sandbox_allowed_background_modes(), true)) {
        $style['body_background_mode'] = $defaults['body_background_mode'];
    }

    if (!in_array((string) $style['body_background_size'], sandbox_allowed_background_sizes(), true)) {
        $style['body_background_size'] = $defaults['body_background_size'];
    }

    if (!in_array((string) $style['body_background_repeat'], sandbox_allowed_background_repeats(), true)) {
        $style['body_background_repeat'] = $defaults['body_background_repeat'];
    }

    if (!in_array((string) $style['body_background_attachment'], sandbox_allowed_background_attachments(), true)) {
        $style['body_background_attachment'] = $defaults['body_background_attachment'];
    }

    if (!in_array((string) $style['body_background_blend_mode'], sandbox_allowed_background_blends(), true)) {
        $style['body_background_blend_mode'] = $defaults['body_background_blend_mode'];
    }

    if (!in_array((string) $style['page_max_width_unit'], sandbox_allowed_measure_units(), true)) {
        $style['page_max_width_unit'] = $defaults['page_max_width_unit'];
    }

    if (!in_array((string) $style['page_padding_unit'], sandbox_allowed_measure_units(), true)) {
        $style['page_padding_unit'] = $defaults['page_padding_unit'];
    }

    if (!in_array((string) $style['page_slot_gap_unit'], sandbox_allowed_measure_units(), true)) {
        $style['page_slot_gap_unit'] = $defaults['page_slot_gap_unit'];
    }

    if (!in_array((string) $style['page_background_mode'], sandbox_allowed_background_modes(), true)) {
        $style['page_background_mode'] = $defaults['page_background_mode'];
    }

    if (!in_array((string) $style['page_background_size'], sandbox_allowed_background_sizes(), true)) {
        $style['page_background_size'] = $defaults['page_background_size'];
    }

    if (!in_array((string) $style['page_background_repeat'], sandbox_allowed_background_repeats(), true)) {
        $style['page_background_repeat'] = $defaults['page_background_repeat'];
    }

    if (!in_array((string) $style['page_background_attachment'], sandbox_allowed_background_attachments(), true)) {
        $style['page_background_attachment'] = $defaults['page_background_attachment'];
    }

    if (!in_array((string) $style['page_background_blend_mode'], sandbox_allowed_background_blends(), true)) {
        $style['page_background_blend_mode'] = $defaults['page_background_blend_mode'];
    }

    if (!in_array((string) $style['header_visibility_mode'], sandbox_allowed_header_visibility_modes(), true)) {
        $style['header_visibility_mode'] = $defaults['header_visibility_mode'];
    }

    if (!in_array((string) $style['header_navigation_mode'], sandbox_allowed_header_navigation_modes(), true)) {
        $style['header_navigation_mode'] = $defaults['header_navigation_mode'];
    }

    if (!in_array((string) $style['header_layout_mode'], sandbox_allowed_header_layouts(), true)) {
        $style['header_layout_mode'] = $defaults['header_layout_mode'];
    }

    if (!in_array((string) $style['header_padding_unit'], sandbox_allowed_measure_units(), true)) {
        $style['header_padding_unit'] = $defaults['header_padding_unit'];
    }

    if (!in_array((string) $style['header_logo_width_unit'], sandbox_allowed_measure_units(), true)) {
        $style['header_logo_width_unit'] = $defaults['header_logo_width_unit'];
    }

    if (!in_array((string) $style['header_logo_height_unit'], sandbox_allowed_measure_units(), true)) {
        $style['header_logo_height_unit'] = $defaults['header_logo_height_unit'];
    }

    if (!in_array((string) $style['header_logo_max_height_unit'], sandbox_allowed_measure_units(), true)) {
        $style['header_logo_max_height_unit'] = $defaults['header_logo_max_height_unit'];
    }

    if (!in_array((string) $style['header_gap_unit'], sandbox_allowed_measure_units(), true)) {
        $style['header_gap_unit'] = $defaults['header_gap_unit'];
    }

    if (!in_array((string) $style['header_height_unit'], sandbox_allowed_measure_units(), true)) {
        $style['header_height_unit'] = $defaults['header_height_unit'];
    }

    if (!in_array((string) $style['header_logo_scale_mode'], sandbox_allowed_logo_scale_modes(), true)) {
        $style['header_logo_scale_mode'] = $defaults['header_logo_scale_mode'];
    }

    if (!in_array((string) $style['header_background_mode'], sandbox_allowed_background_modes(), true)) {
        $style['header_background_mode'] = $defaults['header_background_mode'];
    }

    if (!in_array((string) $style['header_background_size'], sandbox_allowed_background_sizes(), true)) {
        $style['header_background_size'] = $defaults['header_background_size'];
    }

    if (!in_array((string) $style['header_background_repeat'], sandbox_allowed_background_repeats(), true)) {
        $style['header_background_repeat'] = $defaults['header_background_repeat'];
    }

    if (!in_array((string) $style['header_background_attachment'], sandbox_allowed_background_attachments(), true)) {
        $style['header_background_attachment'] = $defaults['header_background_attachment'];
    }

    if (!in_array((string) $style['header_background_blend_mode'], sandbox_allowed_background_blends(), true)) {
        $style['header_background_blend_mode'] = $defaults['header_background_blend_mode'];
    }

    if (!in_array((string) $style['footer_visibility_mode'], sandbox_allowed_header_visibility_modes(), true)) {
        $style['footer_visibility_mode'] = $defaults['footer_visibility_mode'];
    }

    if (!in_array((string) $style['footer_navigation_mode'], sandbox_allowed_header_navigation_modes(), true)) {
        $style['footer_navigation_mode'] = $defaults['footer_navigation_mode'];
    }

    if (!in_array((string) $style['footer_layout_mode'], sandbox_allowed_header_layouts(), true)) {
        $style['footer_layout_mode'] = $defaults['footer_layout_mode'];
    }

    if (!in_array((string) $style['footer_padding_unit'], sandbox_allowed_measure_units(), true)) {
        $style['footer_padding_unit'] = $defaults['footer_padding_unit'];
    }

    if (!in_array((string) $style['footer_gap_unit'], sandbox_allowed_measure_units(), true)) {
        $style['footer_gap_unit'] = $defaults['footer_gap_unit'];
    }

    if (!in_array((string) $style['footer_height_unit'], sandbox_allowed_measure_units(), true)) {
        $style['footer_height_unit'] = $defaults['footer_height_unit'];
    }

    if (!in_array((string) $style['footer_background_mode'], sandbox_allowed_background_modes(), true)) {
        $style['footer_background_mode'] = $defaults['footer_background_mode'];
    }

    if (!in_array((string) $style['footer_background_size'], sandbox_allowed_background_sizes(), true)) {
        $style['footer_background_size'] = $defaults['footer_background_size'];
    }

    if (!in_array((string) $style['footer_background_repeat'], sandbox_allowed_background_repeats(), true)) {
        $style['footer_background_repeat'] = $defaults['footer_background_repeat'];
    }

    if (!in_array((string) $style['footer_background_attachment'], sandbox_allowed_background_attachments(), true)) {
        $style['footer_background_attachment'] = $defaults['footer_background_attachment'];
    }

    if (!in_array((string) $style['footer_background_blend_mode'], sandbox_allowed_background_blends(), true)) {
        $style['footer_background_blend_mode'] = $defaults['footer_background_blend_mode'];
    }

    $style['body_background_color'] = trim((string) ($style['body_background_color'] ?? $defaults['body_background_color'])) ?: $defaults['body_background_color'];
    $style['body_background_gradient_from'] = trim((string) ($style['body_background_gradient_from'] ?? $defaults['body_background_gradient_from'])) ?: $defaults['body_background_gradient_from'];
    $style['body_background_gradient_to'] = trim((string) ($style['body_background_gradient_to'] ?? $defaults['body_background_gradient_to'])) ?: $defaults['body_background_gradient_to'];
    $style['body_background_gradient_angle'] = is_numeric($style['body_background_gradient_angle'] ?? null) ? (float) $style['body_background_gradient_angle'] : $defaults['body_background_gradient_angle'];
    $style['body_background_image_url'] = trim((string) ($style['body_background_image_url'] ?? $defaults['body_background_image_url']));
    $style['body_background_position'] = trim((string) ($style['body_background_position'] ?? $defaults['body_background_position'])) ?: $defaults['body_background_position'];
    $style['page_max_width_value'] = is_numeric($style['page_max_width_value'] ?? null) ? (float) $style['page_max_width_value'] : $defaults['page_max_width_value'];
    $style['page_padding_value'] = is_numeric($style['page_padding_value'] ?? null) ? (float) $style['page_padding_value'] : $defaults['page_padding_value'];
    $style['page_slot_gap_value'] = is_numeric($style['page_slot_gap_value'] ?? null) ? (float) $style['page_slot_gap_value'] : $defaults['page_slot_gap_value'];
    $style['page_background_color'] = trim((string) ($style['page_background_color'] ?? $defaults['page_background_color'])) ?: $defaults['page_background_color'];
    $style['page_background_gradient_from'] = trim((string) ($style['page_background_gradient_from'] ?? $defaults['page_background_gradient_from'])) ?: $defaults['page_background_gradient_from'];
    $style['page_background_gradient_to'] = trim((string) ($style['page_background_gradient_to'] ?? $defaults['page_background_gradient_to'])) ?: $defaults['page_background_gradient_to'];
    $style['page_background_gradient_angle'] = is_numeric($style['page_background_gradient_angle'] ?? null) ? (float) $style['page_background_gradient_angle'] : $defaults['page_background_gradient_angle'];
    $style['page_background_image_url'] = trim((string) ($style['page_background_image_url'] ?? $defaults['page_background_image_url']));
    $style['page_background_position'] = trim((string) ($style['page_background_position'] ?? $defaults['page_background_position'])) ?: $defaults['page_background_position'];
    $style['header_brand_label'] = trim((string) ($style['header_brand_label'] ?? $defaults['header_brand_label']));
    $style['header_logo_url'] = trim((string) ($style['header_logo_url'] ?? $defaults['header_logo_url']));
    $style['header_logo_width_value'] = is_numeric($style['header_logo_width_value'] ?? null) ? (float) $style['header_logo_width_value'] : $defaults['header_logo_width_value'];
    $style['header_logo_height_value'] = is_numeric($style['header_logo_height_value'] ?? null) ? (float) $style['header_logo_height_value'] : $defaults['header_logo_height_value'];
    $style['header_logo_max_height_value'] = is_numeric($style['header_logo_max_height_value'] ?? null) ? (float) $style['header_logo_max_height_value'] : $defaults['header_logo_max_height_value'];
    $style['header_padding_value'] = is_numeric($style['header_padding_value'] ?? null) ? (float) $style['header_padding_value'] : $defaults['header_padding_value'];
    $style['header_height_value'] = is_numeric($style['header_height_value'] ?? null) ? (float) $style['header_height_value'] : $defaults['header_height_value'];
    $style['header_gap_value'] = is_numeric($style['header_gap_value'] ?? null) ? (float) $style['header_gap_value'] : $defaults['header_gap_value'];
    $style['header_background_color'] = trim((string) ($style['header_background_color'] ?? $defaults['header_background_color'])) ?: $defaults['header_background_color'];
    $style['header_background_gradient_from'] = trim((string) ($style['header_background_gradient_from'] ?? $defaults['header_background_gradient_from'])) ?: $defaults['header_background_gradient_from'];
    $style['header_background_gradient_to'] = trim((string) ($style['header_background_gradient_to'] ?? $defaults['header_background_gradient_to'])) ?: $defaults['header_background_gradient_to'];
    $style['header_background_gradient_angle'] = is_numeric($style['header_background_gradient_angle'] ?? null) ? (float) $style['header_background_gradient_angle'] : $defaults['header_background_gradient_angle'];
    $style['header_background_image_url'] = trim((string) ($style['header_background_image_url'] ?? $defaults['header_background_image_url']));
    $style['header_background_position'] = trim((string) ($style['header_background_position'] ?? $defaults['header_background_position'])) ?: $defaults['header_background_position'];
    $style['footer_label'] = trim((string) ($style['footer_label'] ?? $defaults['footer_label'])) ?: $defaults['footer_label'];
    $style['footer_padding_value'] = is_numeric($style['footer_padding_value'] ?? null) ? (float) $style['footer_padding_value'] : $defaults['footer_padding_value'];
    $style['footer_height_value'] = is_numeric($style['footer_height_value'] ?? null) ? (float) $style['footer_height_value'] : $defaults['footer_height_value'];
    $style['footer_gap_value'] = is_numeric($style['footer_gap_value'] ?? null) ? (float) $style['footer_gap_value'] : $defaults['footer_gap_value'];
    $style['footer_background_color'] = trim((string) ($style['footer_background_color'] ?? $defaults['footer_background_color'])) ?: $defaults['footer_background_color'];
    $style['footer_background_gradient_from'] = trim((string) ($style['footer_background_gradient_from'] ?? $defaults['footer_background_gradient_from'])) ?: $defaults['footer_background_gradient_from'];
    $style['footer_background_gradient_to'] = trim((string) ($style['footer_background_gradient_to'] ?? $defaults['footer_background_gradient_to'])) ?: $defaults['footer_background_gradient_to'];
    $style['footer_background_gradient_angle'] = is_numeric($style['footer_background_gradient_angle'] ?? null) ? (float) $style['footer_background_gradient_angle'] : $defaults['footer_background_gradient_angle'];
    $style['footer_background_image_url'] = trim((string) ($style['footer_background_image_url'] ?? $defaults['footer_background_image_url']));
    $style['footer_background_position'] = trim((string) ($style['footer_background_position'] ?? $defaults['footer_background_position'])) ?: $defaults['footer_background_position'];

    return $style;
}

function sandbox_allowed_model_types(): array
{
    return ['section', 'template', 'skin', 'configuration'];
}

function next_sandbox_binding_sort_order(PDO $pdo, int $versionId, string $slotKey): int
{
    $statement = $pdo->prepare(
        'SELECT COALESCE(MAX(sort_order), 0)
        FROM sandbox_bindings
        WHERE sandbox_model_version_id = :sandbox_model_version_id
          AND slot_key = :slot_key'
    );

    $statement->execute([
        'sandbox_model_version_id' => $versionId,
        'slot_key' => $slotKey,
    ]);

    return ((int) $statement->fetchColumn()) + 10;
}

function resequence_sandbox_bindings(PDO $pdo, int $versionId, string $slotKey): void
{
    $statement = $pdo->prepare(
        'SELECT id
        FROM sandbox_bindings
        WHERE sandbox_model_version_id = :sandbox_model_version_id
          AND slot_key = :slot_key
        ORDER BY sort_order ASC, id ASC'
    );

    $statement->execute([
        'sandbox_model_version_id' => $versionId,
        'slot_key' => $slotKey,
    ]);

    $update = $pdo->prepare(
        'UPDATE sandbox_bindings
        SET sort_order = :sort_order
        WHERE id = :id
          AND sandbox_model_version_id = :sandbox_model_version_id'
    );

    $sortOrder = 10;

    foreach ($statement->fetchAll() as $binding) {
        $update->execute([
            'sort_order' => $sortOrder,
            'id' => $binding['id'],
            'sandbox_model_version_id' => $versionId,
        ]);
        $sortOrder += 10;
    }
}

function sandbox_normalize_model_key(string $value): string
{
    $value = strtolower(trim($value));
    $value = preg_replace('/[^a-z0-9]+/', '_', $value) ?? '';

    return trim($value, '_');
}

function sandbox_normalize_media_filename_base(string $value, string $fallback = 'media'): string
{
    $value = strtolower(trim($value));

    if (function_exists('iconv')) {
        $converted = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value);
        if (is_string($converted) && $converted !== '') {
            $value = $converted;
        }
    }

    $value = preg_replace('/[^a-z0-9]+/', '-', $value) ?? '';
    $value = trim($value, '-');

    return $value !== '' ? $value : $fallback;
}

function sandbox_parse_slots(string $value): array
{
    $chunks = preg_split('/[\s,]+/', strtolower($value)) ?: [];
    $slots = [];

    foreach ($chunks as $chunk) {
        $slot = sandbox_normalize_model_key($chunk);

        if ($slot !== '' && !in_array($slot, $slots, true)) {
            $slots[] = $slot;
        }
    }

    return $slots !== [] ? $slots : ['header', 'navigation', 'hero', 'main', 'aside', 'footer_navigation', 'footer'];
}

function sandbox_normalize_frame_slots(array $slots): array
{
    $normalized = [];

    foreach ($slots as $slot) {
        $slotKey = sandbox_normalize_model_key((string) $slot);

        if ($slotKey !== '' && !in_array($slotKey, $normalized, true)) {
            $normalized[] = $slotKey;
        }
    }

    if (!in_array('header', $normalized, true)) {
        array_unshift($normalized, 'header');
    }

    if (!in_array('navigation', $normalized, true)) {
        array_splice($normalized, min(1, count($normalized)), 0, ['navigation']);
    }

    if (!in_array('footer_navigation', $normalized, true)) {
        $footerIndex = array_search('footer', $normalized, true);
        if ($footerIndex === false) {
            $normalized[] = 'footer_navigation';
        } else {
            array_splice($normalized, max(0, $footerIndex), 0, ['footer_navigation']);
        }
    }

    if (!in_array('footer', $normalized, true)) {
        $normalized[] = 'footer';
    }

    return $normalized;
}

function sandbox_label_for_slot(string $slot): string
{
    return match ($slot) {
        'header' => 'Masthead',
        'navigation' => 'Navigation',
        'footer_navigation' => 'Footer Navigation',
        'footer' => 'Postscript',
        default => ucwords(str_replace('_', ' ', $slot)),
    };
}

function sandbox_hint_for_slot(string $slot): string
{
    return match ($slot) {
        'header' => 'Sezione alta del contenuto, distinta dalla cornice header di sistema.',
        'navigation' => 'Slot di sistema per menu, breadcrumb, utility e CTA del chrome pagina.',
        'footer_navigation' => 'Slot di sistema per la navbar inferiore, link di servizio e breadcrumb secondario.',
        'footer' => 'Sezione finale del contenuto, distinta dalla cornice footer di sistema.',
        default => 'Slot pronto per binding reali.',
    };
}

function sandbox_default_slot_settings(string $slot): array
{
    return [
        'label' => sandbox_label_for_slot($slot),
        'layout_mode' => in_array($slot, ['header', 'footer'], true) ? 'split' : 'stack',
        'padding_value' => 24,
        'padding_unit' => 'px',
        'gap_value' => 12,
        'gap_unit' => 'px',
        'visibility_mode' => 'visible',
        'background_mode' => 'none',
        'background_color' => '#161616',
        'background_gradient_from' => '#1f1f1f',
        'background_gradient_to' => '#101010',
        'background_gradient_angle' => 135,
        'background_image_url' => '',
        'background_position' => 'center center',
        'background_size' => 'cover',
        'background_repeat' => 'no-repeat',
        'background_attachment' => 'scroll',
        'background_blend_mode' => 'normal',
    ];
}

function sandbox_normalize_slot_settings(string $slot, array $settings): array
{
    $normalized = sandbox_default_slot_settings($slot);
    $layoutMode = (string) ($settings['layout_mode'] ?? $normalized['layout_mode']);
    $paddingValue = is_numeric($settings['padding_value'] ?? null) ? (float) $settings['padding_value'] : $normalized['padding_value'];
    $paddingUnit = (string) ($settings['padding_unit'] ?? $normalized['padding_unit']);
    $gapValue = is_numeric($settings['gap_value'] ?? null) ? (float) $settings['gap_value'] : $normalized['gap_value'];
    $gapUnit = (string) ($settings['gap_unit'] ?? $normalized['gap_unit']);
    $visibilityMode = (string) ($settings['visibility_mode'] ?? $normalized['visibility_mode']);
    $label = trim((string) ($settings['label'] ?? $normalized['label']));
    $backgroundMode = (string) ($settings['background_mode'] ?? $normalized['background_mode']);
    $backgroundColor = trim((string) ($settings['background_color'] ?? $normalized['background_color']));
    $backgroundGradientFrom = trim((string) ($settings['background_gradient_from'] ?? $normalized['background_gradient_from']));
    $backgroundGradientTo = trim((string) ($settings['background_gradient_to'] ?? $normalized['background_gradient_to']));
    $backgroundGradientAngle = is_numeric($settings['background_gradient_angle'] ?? null) ? (float) $settings['background_gradient_angle'] : $normalized['background_gradient_angle'];
    $backgroundImageUrl = trim((string) ($settings['background_image_url'] ?? $normalized['background_image_url']));
    $backgroundPosition = trim((string) ($settings['background_position'] ?? $normalized['background_position']));
    $backgroundSize = (string) ($settings['background_size'] ?? $normalized['background_size']);
    $backgroundRepeat = (string) ($settings['background_repeat'] ?? $normalized['background_repeat']);
    $backgroundAttachment = (string) ($settings['background_attachment'] ?? $normalized['background_attachment']);
    $backgroundBlendMode = (string) ($settings['background_blend_mode'] ?? $normalized['background_blend_mode']);

    if (!in_array($layoutMode, sandbox_allowed_slot_layouts(), true)) {
        $layoutMode = $normalized['layout_mode'];
    }

    if (!in_array($paddingUnit, sandbox_allowed_measure_units(), true)) {
        $paddingUnit = $normalized['padding_unit'];
    }

    if (!in_array($gapUnit, sandbox_allowed_measure_units(), true)) {
        $gapUnit = $normalized['gap_unit'];
    }

    if (!in_array($visibilityMode, sandbox_allowed_slot_visibility_modes(), true)) {
        $visibilityMode = $normalized['visibility_mode'];
    }

    if (!in_array($backgroundMode, sandbox_allowed_background_modes(), true)) {
        $backgroundMode = $normalized['background_mode'];
    }

    if (!in_array($backgroundSize, sandbox_allowed_background_sizes(), true)) {
        $backgroundSize = $normalized['background_size'];
    }

    if (!in_array($backgroundRepeat, sandbox_allowed_background_repeats(), true)) {
        $backgroundRepeat = $normalized['background_repeat'];
    }

    if (!in_array($backgroundAttachment, sandbox_allowed_background_attachments(), true)) {
        $backgroundAttachment = $normalized['background_attachment'];
    }

    if (!in_array($backgroundBlendMode, sandbox_allowed_background_blends(), true)) {
        $backgroundBlendMode = $normalized['background_blend_mode'];
    }

    $normalized['label'] = $label !== '' ? $label : $normalized['label'];
    $normalized['layout_mode'] = $layoutMode;
    $normalized['padding_value'] = $paddingValue;
    $normalized['padding_unit'] = $paddingUnit;
    $normalized['gap_value'] = $gapValue;
    $normalized['gap_unit'] = $gapUnit;
    $normalized['visibility_mode'] = $visibilityMode;
    $normalized['background_mode'] = $backgroundMode;
    $normalized['background_color'] = $backgroundColor !== '' ? $backgroundColor : $normalized['background_color'];
    $normalized['background_gradient_from'] = $backgroundGradientFrom !== '' ? $backgroundGradientFrom : $normalized['background_gradient_from'];
    $normalized['background_gradient_to'] = $backgroundGradientTo !== '' ? $backgroundGradientTo : $normalized['background_gradient_to'];
    $normalized['background_gradient_angle'] = $backgroundGradientAngle;
    $normalized['background_image_url'] = $backgroundImageUrl;
    $normalized['background_position'] = $backgroundPosition !== '' ? $backgroundPosition : $normalized['background_position'];
    $normalized['background_size'] = $backgroundSize;
    $normalized['background_repeat'] = $backgroundRepeat;
    $normalized['background_attachment'] = $backgroundAttachment;
    $normalized['background_blend_mode'] = $backgroundBlendMode;

    return $normalized;
}

function sandbox_normalize_all_slot_settings(array $settingsBySlot, array $slots): array
{
    $normalized = [];

    foreach ($slots as $slot) {
        $settings = $settingsBySlot[$slot] ?? [];
        $normalized[$slot] = sandbox_normalize_slot_settings($slot, is_array($settings) ? $settings : []);
    }

    return $normalized;
}

function sandbox_merge_preview_placeholders(array $preview, array $slots): array
{
    $existing = [];

    foreach (($preview['placeholders'] ?? []) as $placeholder) {
        $slot = sandbox_normalize_model_key((string) ($placeholder['slot'] ?? ''));

        if ($slot !== '') {
            $existing[$slot] = true;
        }
    }

    foreach ($slots as $slot) {
        if (isset($existing[$slot])) {
            continue;
        }

        $preview['placeholders'][] = [
            'label' => sandbox_label_for_slot($slot),
            'hint' => sandbox_hint_for_slot($slot),
            'slot' => $slot,
        ];
    }

    return $preview;
}

function fetch_sandbox_default_setup(PDO $pdo): array
{
    $templateId = (int) ($pdo->query("SELECT id FROM templates ORDER BY id ASC LIMIT 1")->fetchColumn() ?: 0);
    $skinId = (int) ($pdo->query("SELECT id FROM skins ORDER BY id ASC LIMIT 1")->fetchColumn() ?: 0);
    $contentTypeId = (int) ($pdo->query("SELECT id FROM content_types WHERE type_key = 'page' ORDER BY id ASC LIMIT 1")->fetchColumn() ?: 0);
    $dataSourceId = (int) ($pdo->query("SELECT id FROM data_sources WHERE source_key = 'content_items' ORDER BY id ASC LIMIT 1")->fetchColumn() ?: 0);

    return [
        'template_id' => $templateId > 0 ? $templateId : null,
        'skin_id' => $skinId > 0 ? $skinId : null,
        'content_type_id' => $contentTypeId > 0 ? $contentTypeId : null,
        'data_source_id' => $dataSourceId > 0 ? $dataSourceId : null,
    ];
}

function create_sandbox_model(PDO $pdo, array $input): ?string
{
    $name = trim((string) ($input['new_model_name'] ?? ''));
    $type = (string) ($input['new_model_type'] ?? 'section');
    $modelKey = sandbox_normalize_model_key((string) ($input['new_model_key'] ?? $name));
    $slots = sandbox_normalize_frame_slots(
        sandbox_parse_slots((string) ($input['new_model_slots'] ?? 'header, navigation, hero, main, aside, footer_navigation, footer'))
    );

    if ($name === '' || $modelKey === '' || !in_array($type, sandbox_allowed_model_types(), true)) {
        return null;
    }

    $exists = $pdo->prepare(
        'SELECT id
        FROM sandbox_models
        WHERE model_type = :model_type
          AND model_key = :model_key
        LIMIT 1'
    );
    $exists->execute([
        'model_type' => $type,
        'model_key' => $modelKey,
    ]);

    if ($exists->fetchColumn()) {
        return $modelKey;
    }

    $defaults = fetch_sandbox_default_setup($pdo);

    $insertModel = $pdo->prepare(
        'INSERT INTO sandbox_models (
            model_type,
            model_key,
            name,
            status,
            linked_template_id,
            linked_skin_id,
            preview_content_type_id,
            preview_data_source_id,
            notes,
            created_by,
            updated_by
        ) VALUES (
            :model_type,
            :model_key,
            :name,
            :status,
            :linked_template_id,
            :linked_skin_id,
            :preview_content_type_id,
            :preview_data_source_id,
            :notes,
            :created_by,
            :updated_by
        )'
    );

    $insertModel->execute([
        'model_type' => $type,
        'model_key' => $modelKey,
        'name' => $name,
        'status' => 'draft',
        'linked_template_id' => $defaults['template_id'],
        'linked_skin_id' => $defaults['skin_id'],
        'preview_content_type_id' => $defaults['content_type_id'],
        'preview_data_source_id' => $defaults['data_source_id'],
        'notes' => 'Modello creato da UI sandbox.',
        'created_by' => null,
        'updated_by' => null,
    ]);

    $sandboxModelId = (int) $pdo->lastInsertId();
    $placeholderRows = [];

    foreach ($slots as $slot) {
        $placeholderRows[] = [
            'label' => sandbox_label_for_slot($slot),
            'hint' => sandbox_hint_for_slot($slot),
            'slot' => $slot,
        ];
    }

    $insertVersion = $pdo->prepare(
        'INSERT INTO sandbox_model_versions (
            sandbox_model_id,
            version_no,
            structure_json,
            style_json,
            data_contract_json,
            preview_json,
            change_notes,
            created_by
        ) VALUES (
            :sandbox_model_id,
            :version_no,
            :structure_json,
            :style_json,
            :data_contract_json,
            :preview_json,
            :change_notes,
            :created_by
        )'
    );

    $insertVersion->execute([
        'sandbox_model_id' => $sandboxModelId,
        'version_no' => 1,
        'structure_json' => json_encode([
            'frame' => $slots,
            'grid_mode' => 'snap_soft',
            'selected_slot' => $slots[0] ?? 'hero',
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        'style_json' => json_encode([
            'skin_level' => 'editorial_dark',
            'grid' => 'enabled',
            'body_background_mode' => 'none',
            'body_background_color' => '#101010',
            'body_background_gradient_from' => '#161616',
            'body_background_gradient_to' => '#090909',
            'body_background_gradient_angle' => 135,
            'body_background_image_url' => '',
            'body_background_position' => 'center center',
            'body_background_size' => 'cover',
            'body_background_repeat' => 'no-repeat',
            'body_background_attachment' => 'scroll',
            'body_background_blend_mode' => 'normal',
            'page_max_width_value' => 100,
            'page_max_width_unit' => 'percent',
            'page_padding_value' => 18,
            'page_padding_unit' => 'px',
            'page_slot_gap_value' => 12,
            'page_slot_gap_unit' => 'px',
            'page_background_mode' => 'none',
            'page_background_color' => '#121212',
            'page_background_gradient_from' => '#1a1a1a',
            'page_background_gradient_to' => '#0d0d0d',
            'page_background_gradient_angle' => 180,
            'page_background_image_url' => '',
            'page_background_position' => 'center center',
            'page_background_size' => 'cover',
            'page_background_repeat' => 'no-repeat',
            'page_background_attachment' => 'scroll',
            'page_background_blend_mode' => 'normal',
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        'data_contract_json' => json_encode([
            'rules' => [
                'record singolo o lista da definire',
                'binding dal registry sorgenti dati',
                'slot guidati da struttura sandbox',
            ],
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        'preview_json' => json_encode([
            'title' => $name,
            'subtitle' => 'Nuovo modello sandbox pronto per slot, binding e versioni.',
            'panels' => ['modelli', 'binding', 'preview'],
            'placeholders' => $placeholderRows,
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        'change_notes' => 'Versione iniziale creata da UI sandbox',
        'created_by' => null,
    ]);

    return $modelKey;
}

function fetch_sandbox_model_list(PDO $pdo): array
{
    $statement = $pdo->query(
        'SELECT model_key, name, model_type, status
        FROM sandbox_models
        ORDER BY name ASC'
    );

    $items = [];

    foreach ($statement->fetchAll() as $item) {
        $items[] = $item;
    }

    return $items;
}

function fetch_sandbox_versions(PDO $pdo, int $sandboxModelId): array
{
    $statement = $pdo->prepare(
        'SELECT id, version_no, change_notes, created_at
        FROM sandbox_model_versions
        WHERE sandbox_model_id = :sandbox_model_id
        ORDER BY version_no DESC'
    );

    $statement->execute([
        'sandbox_model_id' => $sandboxModelId,
    ]);

    return $statement->fetchAll();
}

function fetch_bindable_data_sources(PDO $pdo): array
{
    $statement = $pdo->query(
        'SELECT id, source_key, label, source_type
        FROM data_sources
        WHERE is_bindable = 1
          AND is_active = 1
        ORDER BY label ASC'
    );

    $sources = [];

    foreach ($statement->fetchAll() as $source) {
        $fieldStatement = $pdo->prepare(
            'SELECT dsf.field_key, dsf.label, dsf.field_type
            FROM data_source_fields dsf
            INNER JOIN data_sources ds ON ds.id = dsf.data_source_id
            WHERE ds.source_key = :source_key
              AND dsf.is_bindable = 1
            ORDER BY dsf.id ASC'
        );

        $fieldStatement->execute([
            'source_key' => $source['source_key'],
        ]);

        $source['fields'] = $fieldStatement->fetchAll();

        if ($source['fields'] !== []) {
            $sources[] = $source;
        }
    }

    return $sources;
}

function find_data_source_by_key(array $sources, string $sourceKey): ?array
{
    foreach ($sources as $source) {
        if (($source['source_key'] ?? null) === $sourceKey) {
            return $source;
        }
    }

    return null;
}

function resolve_sandbox_style_target(PDO $pdo, array $model, ?int $versionNo = null): ?array
{
    if (!is_array($model['version'] ?? null)) {
        return null;
    }

    if (($model['model_type'] ?? null) === 'template') {
        return $model;
    }

    $linkedTemplateId = (int) ($model['linked_template_id'] ?? 0);
    if ($linkedTemplateId <= 0) {
        return $model;
    }

    $statement = $pdo->prepare(
        'SELECT model_key
        FROM sandbox_models
        WHERE linked_template_id = :linked_template_id
          AND model_type = :model_type
        ORDER BY id ASC
        LIMIT 1'
    );

    $statement->execute([
        'linked_template_id' => $linkedTemplateId,
        'model_type' => 'template',
    ]);

    $templateModelKey = $statement->fetchColumn();
    if (!is_string($templateModelKey) || $templateModelKey === '') {
        return $model;
    }

    $templateModel = fetch_sandbox_model_by_key($pdo, $templateModelKey, null, $model['selected_source_key'] ?? null);

    return $templateModel ?? $model;
}

function update_sandbox_version_state(PDO $pdo, string $modelKey, array $input): void
{
    $versionNo = !empty($input['version']) ? (int) $input['version'] : null;
    $model = fetch_sandbox_model_by_key($pdo, $modelKey, $versionNo);

    if ($model === null || !is_array($model['version'] ?? null)) {
        return;
    }

    $version = $model['version'];
    $structure = $version['structure_json'] ?? [];
    $style = $version['style_json'] ?? [];
    $selectedFrame = (string) ($input['selected_frame'] ?? '');

    if ($selectedFrame !== '') {
        $allowedFrames = ['header', 'footer'];
        if (in_array($selectedFrame, $allowedFrames, true)) {
            $structure['selected_frame'] = $selectedFrame;
            unset($structure['selected_binding_id']);
            unset($structure['selected_binding_focus']);
        }
    } elseif (array_key_exists('selected_frame', $input)) {
        unset($structure['selected_frame']);
    }

    $selectedSlot = (string) ($input['selected_slot'] ?? '');
    if ($selectedSlot !== '' && in_array($selectedSlot, $structure['frame'] ?? [], true)) {
        $slotChanged = ($structure['selected_slot'] ?? null) !== $selectedSlot;
        $structure['selected_slot'] = $selectedSlot;
        unset($structure['selected_frame']);

        if ($slotChanged && !array_key_exists('selected_binding_id', $input)) {
            unset($structure['selected_binding_id']);
            unset($structure['selected_binding_focus']);
        }
    }

    $selectedBindingId = isset($input['selected_binding_id']) ? (int) $input['selected_binding_id'] : 0;
    if ($selectedBindingId > 0) {
        $bindingStatement = $pdo->prepare(
            'SELECT id, slot_key
            FROM sandbox_bindings
            WHERE id = :id
              AND sandbox_model_version_id = :sandbox_model_version_id
            LIMIT 1'
        );

        $bindingStatement->execute([
            'id' => $selectedBindingId,
            'sandbox_model_version_id' => $version['id'],
        ]);

        $binding = $bindingStatement->fetch();

        if (is_array($binding)) {
            $structure['selected_binding_id'] = (int) $binding['id'];
            $structure['selected_slot'] = (string) $binding['slot_key'];
            unset($structure['selected_frame']);
            $bindingFocus = (string) ($input['selected_binding_focus'] ?? 'binding');
            $structure['selected_binding_focus'] = in_array($bindingFocus, ['binding', 'media'], true) ? $bindingFocus : 'binding';
        }
    } elseif (array_key_exists('selected_binding_id', $input)) {
        unset($structure['selected_binding_id']);
        unset($structure['selected_binding_focus']);
    }

    $gridMode = (string) ($input['grid_mode'] ?? '');
    if ($gridMode !== '' && in_array($gridMode, sandbox_allowed_grid_modes(), true)) {
        $structure['grid_mode'] = $gridMode;
        $style['grid'] = $gridMode === 'snap_off' ? 'disabled' : 'enabled';
    }

    $statement = $pdo->prepare(
        'UPDATE sandbox_model_versions
        SET structure_json = :structure_json,
            style_json = :style_json
        WHERE id = :id'
    );

    $statement->execute([
        'structure_json' => json_encode($structure, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        'style_json' => json_encode($style, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        'id' => $version['id'],
    ]);
}

function update_sandbox_slot_options(PDO $pdo, string $modelKey, array $input): void
{
    $versionNo = !empty($input['version']) ? (int) $input['version'] : null;
    $model = fetch_sandbox_model_by_key($pdo, $modelKey, $versionNo);

    if ($model === null || !is_array($model['version'] ?? null)) {
        return;
    }

    $version = $model['version'];
    $structure = $version['structure_json'] ?? [];
    $slotKey = (string) ($input['selected_slot'] ?? $input['slot_key'] ?? '');

    if ($slotKey === '' || !in_array($slotKey, $structure['frame'] ?? [], true)) {
        return;
    }

    $slotSettings = $structure['slot_settings'] ?? [];
    $slotSettings[$slotKey] = sandbox_normalize_slot_settings($slotKey, [
        'label' => $input['slot_label'] ?? null,
        'layout_mode' => $input['slot_layout_mode'] ?? null,
        'padding_value' => $input['slot_padding_value'] ?? null,
        'padding_unit' => $input['slot_padding_unit'] ?? null,
        'gap_value' => $input['slot_gap_value'] ?? null,
        'gap_unit' => $input['slot_gap_unit'] ?? null,
        'visibility_mode' => $input['slot_visibility_mode'] ?? null,
    ]);

    $structure['slot_settings'] = sandbox_normalize_all_slot_settings($slotSettings, $structure['frame'] ?? []);
    $structure['selected_slot'] = $slotKey;
    unset($structure['selected_binding_id']);

    $statement = $pdo->prepare(
        'UPDATE sandbox_model_versions
        SET structure_json = :structure_json
        WHERE id = :id'
    );

    $statement->execute([
        'structure_json' => json_encode($structure, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        'id' => $version['id'],
    ]);
}

function update_sandbox_body_background(PDO $pdo, string $modelKey, array $input): void
{
    $versionNo = !empty($input['version']) ? (int) $input['version'] : null;
    $model = fetch_sandbox_model_by_key($pdo, $modelKey, $versionNo);

    if ($model === null || !is_array($model['version'] ?? null)) {
        return;
    }

    $version = $model['version'];
    $style = sandbox_normalize_style_profile($version['style_json'] ?? []);
    $style = sandbox_normalize_style_profile(array_merge($style, [
        'body_background_mode' => $input['body_background_mode'] ?? null,
        'body_background_color' => $input['body_background_color'] ?? null,
        'body_background_gradient_from' => $input['body_background_gradient_from'] ?? null,
        'body_background_gradient_to' => $input['body_background_gradient_to'] ?? null,
        'body_background_gradient_angle' => $input['body_background_gradient_angle'] ?? null,
        'body_background_image_url' => $input['body_background_image_url'] ?? null,
        'body_background_position' => $input['body_background_position'] ?? null,
        'body_background_size' => $input['body_background_size'] ?? null,
        'body_background_repeat' => $input['body_background_repeat'] ?? null,
        'body_background_attachment' => $input['body_background_attachment'] ?? null,
        'body_background_blend_mode' => $input['body_background_blend_mode'] ?? null,
    ]));

    $statement = $pdo->prepare(
        'UPDATE sandbox_model_versions
        SET style_json = :style_json
        WHERE id = :id'
    );

    $statement->execute([
        'style_json' => json_encode($style, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        'id' => $version['id'],
    ]);
}

function update_sandbox_page_canvas(PDO $pdo, string $modelKey, array $input): void
{
    $versionNo = !empty($input['version']) ? (int) $input['version'] : null;
    $model = fetch_sandbox_model_by_key($pdo, $modelKey, $versionNo);

    if ($model === null || !is_array($model['version'] ?? null)) {
        return;
    }

    $target = resolve_sandbox_style_target($pdo, $model, $versionNo);
    if ($target === null || !is_array($target['version'] ?? null)) {
        return;
    }

    $version = $target['version'];
    $style = sandbox_normalize_style_profile($version['style_json'] ?? []);
    $style = sandbox_normalize_style_profile(array_merge($style, [
        'page_max_width_value' => $input['page_max_width_value'] ?? null,
        'page_max_width_unit' => $input['page_max_width_unit'] ?? null,
        'page_padding_value' => $input['page_padding_value'] ?? null,
        'page_padding_unit' => $input['page_padding_unit'] ?? null,
        'page_slot_gap_value' => $input['page_slot_gap_value'] ?? null,
        'page_slot_gap_unit' => $input['page_slot_gap_unit'] ?? null,
        'page_background_mode' => $input['page_background_mode'] ?? null,
        'page_background_color' => $input['page_background_color'] ?? null,
        'page_background_gradient_from' => $input['page_background_gradient_from'] ?? null,
        'page_background_gradient_to' => $input['page_background_gradient_to'] ?? null,
        'page_background_gradient_angle' => $input['page_background_gradient_angle'] ?? null,
        'page_background_image_url' => $input['page_background_image_url'] ?? null,
        'page_background_position' => $input['page_background_position'] ?? null,
        'page_background_size' => $input['page_background_size'] ?? null,
        'page_background_repeat' => $input['page_background_repeat'] ?? null,
        'page_background_attachment' => $input['page_background_attachment'] ?? null,
        'page_background_blend_mode' => $input['page_background_blend_mode'] ?? null,
    ]));

    $statement = $pdo->prepare(
        'UPDATE sandbox_model_versions
        SET style_json = :style_json
        WHERE id = :id'
    );

    $statement->execute([
        'style_json' => json_encode($style, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        'id' => $version['id'],
    ]);
}

function update_sandbox_header_options(PDO $pdo, string $modelKey, array $input): void
{
    $versionNo = !empty($input['version']) ? (int) $input['version'] : null;
    $model = fetch_sandbox_model_by_key($pdo, $modelKey, $versionNo);

    if ($model === null || !is_array($model['version'] ?? null)) {
        return;
    }

    $target = resolve_sandbox_style_target($pdo, $model, $versionNo);
    if ($target === null || !is_array($target['version'] ?? null)) {
        return;
    }

    $version = $target['version'];
    $style = sandbox_normalize_style_profile($version['style_json'] ?? []);
    $style = sandbox_normalize_style_profile(array_merge($style, [
        'header_brand_label' => $input['header_brand_label'] ?? null,
        'header_logo_url' => $input['header_logo_url'] ?? null,
        'header_logo_width_value' => $input['header_logo_width_value'] ?? null,
        'header_logo_width_unit' => $input['header_logo_width_unit'] ?? null,
        'header_logo_height_value' => $input['header_logo_height_value'] ?? null,
        'header_logo_height_unit' => $input['header_logo_height_unit'] ?? null,
        'header_logo_max_height_value' => $input['header_logo_max_height_value'] ?? null,
        'header_logo_max_height_unit' => $input['header_logo_max_height_unit'] ?? null,
        'header_logo_scale_mode' => $input['header_logo_scale_mode'] ?? null,
        'header_visibility_mode' => $input['header_visibility_mode'] ?? null,
        'header_navigation_mode' => $input['header_navigation_mode'] ?? null,
        'header_layout_mode' => $input['header_layout_mode'] ?? null,
        'header_padding_value' => $input['header_padding_value'] ?? null,
        'header_padding_unit' => $input['header_padding_unit'] ?? null,
        'header_height_value' => $input['header_height_value'] ?? null,
        'header_height_unit' => $input['header_height_unit'] ?? null,
        'header_gap_value' => $input['header_gap_value'] ?? null,
        'header_gap_unit' => $input['header_gap_unit'] ?? null,
        'header_background_mode' => $input['header_background_mode'] ?? null,
        'header_background_color' => $input['header_background_color'] ?? null,
        'header_background_gradient_from' => $input['header_background_gradient_from'] ?? null,
        'header_background_gradient_to' => $input['header_background_gradient_to'] ?? null,
        'header_background_gradient_angle' => $input['header_background_gradient_angle'] ?? null,
        'header_background_image_url' => $input['header_background_image_url'] ?? null,
        'header_background_position' => $input['header_background_position'] ?? null,
        'header_background_size' => $input['header_background_size'] ?? null,
        'header_background_repeat' => $input['header_background_repeat'] ?? null,
        'header_background_attachment' => $input['header_background_attachment'] ?? null,
        'header_background_blend_mode' => $input['header_background_blend_mode'] ?? null,
    ]));

    $statement = $pdo->prepare(
        'UPDATE sandbox_model_versions
        SET style_json = :style_json
        WHERE id = :id'
    );

    $statement->execute([
        'style_json' => json_encode($style, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        'id' => $version['id'],
    ]);
}

function update_sandbox_footer_options(PDO $pdo, string $modelKey, array $input): void
{
    $versionNo = !empty($input['version']) ? (int) $input['version'] : null;
    $model = fetch_sandbox_model_by_key($pdo, $modelKey, $versionNo);

    if ($model === null || !is_array($model['version'] ?? null)) {
        return;
    }

    $target = resolve_sandbox_style_target($pdo, $model, $versionNo);
    if ($target === null || !is_array($target['version'] ?? null)) {
        return;
    }

    $version = $target['version'];
    $style = sandbox_normalize_style_profile($version['style_json'] ?? []);
    $style = sandbox_normalize_style_profile(array_merge($style, [
        'footer_label' => $input['footer_label'] ?? null,
        'footer_visibility_mode' => $input['footer_visibility_mode'] ?? null,
        'footer_navigation_mode' => $input['footer_navigation_mode'] ?? null,
        'footer_layout_mode' => $input['footer_layout_mode'] ?? null,
        'footer_padding_value' => $input['footer_padding_value'] ?? null,
        'footer_padding_unit' => $input['footer_padding_unit'] ?? null,
        'footer_height_value' => $input['footer_height_value'] ?? null,
        'footer_height_unit' => $input['footer_height_unit'] ?? null,
        'footer_gap_value' => $input['footer_gap_value'] ?? null,
        'footer_gap_unit' => $input['footer_gap_unit'] ?? null,
        'footer_background_mode' => $input['footer_background_mode'] ?? null,
        'footer_background_color' => $input['footer_background_color'] ?? null,
        'footer_background_gradient_from' => $input['footer_background_gradient_from'] ?? null,
        'footer_background_gradient_to' => $input['footer_background_gradient_to'] ?? null,
        'footer_background_gradient_angle' => $input['footer_background_gradient_angle'] ?? null,
        'footer_background_image_url' => $input['footer_background_image_url'] ?? null,
        'footer_background_position' => $input['footer_background_position'] ?? null,
        'footer_background_size' => $input['footer_background_size'] ?? null,
        'footer_background_repeat' => $input['footer_background_repeat'] ?? null,
        'footer_background_attachment' => $input['footer_background_attachment'] ?? null,
        'footer_background_blend_mode' => $input['footer_background_blend_mode'] ?? null,
    ]));

    $statement = $pdo->prepare(
        'UPDATE sandbox_model_versions
        SET style_json = :style_json
        WHERE id = :id'
    );

    $statement->execute([
        'style_json' => json_encode($style, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        'id' => $version['id'],
    ]);
}

function upload_sandbox_header_logo(PDO $pdo, string $modelKey, array $input, array $files, string $basePath = ''): ?string
{
    $upload = $files['header_logo_upload'] ?? null;
    if (!is_array($upload) || (int) ($upload['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        return null;
    }

    $tmpPath = (string) ($upload['tmp_name'] ?? '');
    if ($tmpPath === '' || !is_uploaded_file($tmpPath)) {
        return null;
    }

    $config = sandbox_media_library_config('header_logo');
    if ($config === null) {
        return null;
    }

    $originalName = (string) ($upload['name'] ?? 'logo');
    $extension = strtolower((string) pathinfo($originalName, PATHINFO_EXTENSION));
    if (!in_array($extension, $config['allowed_extensions'], true)) {
        return null;
    }

    $directory = $config['directory'];
    if (!is_dir($directory) && !mkdir($directory, 0775, true) && !is_dir($directory)) {
        return null;
    }

    $baseName = sandbox_normalize_media_filename_base((string) pathinfo($originalName, PATHINFO_FILENAME), 'header-logo');
    if ($baseName === '') {
        $baseName = 'header-logo';
    }

    $fileName = $baseName . '-' . date('Ymd-His') . '.' . $extension;
    $targetPath = $directory . '/' . $fileName;

    if (!move_uploaded_file($tmpPath, $targetPath)) {
        return null;
    }

    $publicUrl = rtrim($basePath, '/') . $config['public_path'] . '/' . rawurlencode($fileName);
    $imageInfo = @getimagesize($targetPath);
    $imageWidth = is_array($imageInfo) && isset($imageInfo[0]) ? (int) $imageInfo[0] : null;
    $imageHeight = is_array($imageInfo) && isset($imageInfo[1]) ? (int) $imageInfo[1] : null;
    update_sandbox_header_options($pdo, $modelKey, array_merge($input, [
        'header_logo_url' => $publicUrl,
        'header_logo_width_value' => $imageWidth,
        'header_logo_width_unit' => 'px',
        'header_logo_height_value' => $imageHeight,
        'header_logo_height_unit' => 'px',
        'header_logo_max_height_value' => $imageHeight,
        'header_logo_max_height_unit' => 'px',
    ]));

    return $publicUrl;
}

function create_sandbox_version(PDO $pdo, string $modelKey, ?int $versionNo = null): void
{
    $model = fetch_sandbox_model_by_key($pdo, $modelKey, $versionNo);

    if ($model === null || !is_array($model['version'] ?? null)) {
        return;
    }

    $sourceVersion = $model['version'];
    $nextVersion = ((int) $sourceVersion['version_no']) + 1;

    $insertVersion = $pdo->prepare(
        'INSERT INTO sandbox_model_versions (
            sandbox_model_id,
            version_no,
            structure_json,
            style_json,
            data_contract_json,
            preview_json,
            change_notes,
            created_by
        ) VALUES (
            :sandbox_model_id,
            :version_no,
            :structure_json,
            :style_json,
            :data_contract_json,
            :preview_json,
            :change_notes,
            :created_by
        )'
    );

    $insertVersion->execute([
        'sandbox_model_id' => $sourceVersion['sandbox_model_id'],
        'version_no' => $nextVersion,
        'structure_json' => json_encode($sourceVersion['structure_json'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        'style_json' => json_encode($sourceVersion['style_json'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        'data_contract_json' => json_encode($sourceVersion['data_contract_json'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        'preview_json' => json_encode($sourceVersion['preview_json'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        'change_notes' => 'Cloned from version ' . $sourceVersion['version_no'],
        'created_by' => null,
    ]);

    $newVersionId = (int) $pdo->lastInsertId();

    foreach (($model['bindings'] ?? []) as $binding) {
        $insertBinding = $pdo->prepare(
            'INSERT INTO sandbox_bindings (
                sandbox_model_version_id,
                data_source_id,
                field_key,
                field_alias,
                bind_type,
                slot_key,
                sort_order,
                position_x,
                position_y,
                width_value,
                width_unit,
                height_value,
                height_unit,
                alignment,
                visibility_rule,
                fallback_value,
                media_config_json
            ) VALUES (
                :sandbox_model_version_id,
                :data_source_id,
                :field_key,
                :field_alias,
                :bind_type,
                :slot_key,
                :sort_order,
                :position_x,
                :position_y,
                :width_value,
                :width_unit,
                :height_value,
                :height_unit,
                :alignment,
                :visibility_rule,
                :fallback_value,
                :media_config_json
            )'
        );

        $insertBinding->execute([
            'sandbox_model_version_id' => $newVersionId,
            'data_source_id' => $binding['data_source_id'],
            'field_key' => $binding['field_key'],
            'field_alias' => $binding['field_alias'],
            'bind_type' => $binding['bind_type'],
            'slot_key' => $binding['slot_key'],
            'sort_order' => $binding['sort_order'],
            'position_x' => $binding['position_x'],
            'position_y' => $binding['position_y'],
            'width_value' => $binding['width_value'],
            'width_unit' => $binding['width_unit'],
            'height_value' => $binding['height_value'],
            'height_unit' => $binding['height_unit'],
            'alignment' => $binding['alignment'],
            'visibility_rule' => json_encode($binding['visibility_rule'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'fallback_value' => $binding['fallback_value'],
            'media_config_json' => json_encode($binding['media_config_json'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        ]);
    }
}

function create_sandbox_binding(PDO $pdo, string $modelKey, array $input): void
{
    $versionNo = !empty($input['version']) ? (int) $input['version'] : null;
    $model = fetch_sandbox_model_by_key($pdo, $modelKey, $versionNo);

    if ($model === null || !is_array($model['version'] ?? null)) {
        return;
    }

    $versionId = (int) $model['version']['id'];
    $slotKey = (string) ($input['slot_key'] ?? '');
    $fieldKey = (string) ($input['field_key'] ?? '');
    $fieldAlias = trim((string) ($input['field_alias'] ?? $fieldKey));
    $dataSourceKey = (string) ($input['data_source_key'] ?? '');
    $allowDuplicate = !empty($input['allow_duplicate']);

    if ($slotKey === '' || $fieldKey === '' || $dataSourceKey === '') {
        return;
    }

    $dataSourceStatement = $pdo->prepare(
        'SELECT id
        FROM data_sources
        WHERE source_key = :source_key
          AND is_bindable = 1
        LIMIT 1'
    );

    $dataSourceStatement->execute([
        'source_key' => $dataSourceKey,
    ]);

    $dataSource = $dataSourceStatement->fetch();

    if (!is_array($dataSource)) {
        return;
    }

    $fieldStatement = $pdo->prepare(
        'SELECT dsf.field_key
        FROM data_source_fields dsf
        WHERE dsf.data_source_id = :data_source_id
          AND dsf.field_key = :field_key
          AND dsf.is_bindable = 1
        LIMIT 1'
    );

    $fieldStatement->execute([
        'data_source_id' => $dataSource['id'],
        'field_key' => $fieldKey,
    ]);

    if (!is_array($fieldStatement->fetch())) {
        return;
    }

    if (!$allowDuplicate) {
        $duplicateStatement = $pdo->prepare(
            'SELECT id
            FROM sandbox_bindings
            WHERE sandbox_model_version_id = :sandbox_model_version_id
              AND data_source_id = :data_source_id
              AND field_key = :field_key
              AND slot_key = :slot_key
            LIMIT 1'
        );

        $duplicateStatement->execute([
            'sandbox_model_version_id' => $versionId,
            'data_source_id' => $dataSource['id'],
            'field_key' => $fieldKey,
            'slot_key' => $slotKey,
        ]);

        if (is_array($duplicateStatement->fetch())) {
            return;
        }
    }

    $insert = $pdo->prepare(
        'INSERT INTO sandbox_bindings (
            sandbox_model_version_id,
            data_source_id,
            field_key,
            field_alias,
            bind_type,
            slot_key,
            sort_order,
            position_x,
            position_y,
            width_value,
            width_unit,
            height_value,
            height_unit,
            alignment,
            visibility_rule,
            fallback_value,
            media_config_json
        ) VALUES (
            :sandbox_model_version_id,
            :data_source_id,
            :field_key,
            :field_alias,
            :bind_type,
            :slot_key,
            :sort_order,
            :position_x,
            :position_y,
            :width_value,
            :width_unit,
            :height_value,
            :height_unit,
            :alignment,
            :visibility_rule,
            :fallback_value,
            :media_config_json
        )'
    );

    $insert->execute([
        'sandbox_model_version_id' => $versionId,
        'data_source_id' => $dataSource['id'],
        'field_key' => $fieldKey,
        'field_alias' => $fieldAlias,
        'bind_type' => 'single',
        'slot_key' => $slotKey,
        'sort_order' => next_sandbox_binding_sort_order($pdo, $versionId, $slotKey),
        'position_x' => 0,
        'position_y' => 0,
        'width_value' => 100,
        'width_unit' => 'percent',
        'height_value' => null,
        'height_unit' => null,
        'alignment' => 'start',
        'visibility_rule' => json_encode(new stdClass()),
        'fallback_value' => '',
        'media_config_json' => json_encode(new stdClass()),
    ]);
}

function delete_sandbox_binding(PDO $pdo, string $modelKey, array $input): void
{
    $versionNo = !empty($input['version']) ? (int) $input['version'] : null;
    $model = fetch_sandbox_model_by_key($pdo, $modelKey, $versionNo);

    if ($model === null || !is_array($model['version'] ?? null)) {
        return;
    }

    $bindingId = (int) ($input['binding_id'] ?? 0);
    if ($bindingId <= 0) {
        return;
    }

    $statement = $pdo->prepare(
        'DELETE FROM sandbox_bindings
        WHERE id = :id
          AND sandbox_model_version_id = :sandbox_model_version_id'
    );

    $statement->execute([
        'id' => $bindingId,
        'sandbox_model_version_id' => $model['version']['id'],
    ]);
}

function move_sandbox_binding(PDO $pdo, string $modelKey, array $input): void
{
    $versionNo = !empty($input['version']) ? (int) $input['version'] : null;
    $model = fetch_sandbox_model_by_key($pdo, $modelKey, $versionNo);

    if ($model === null || !is_array($model['version'] ?? null)) {
        return;
    }

    $bindingId = (int) ($input['binding_id'] ?? 0);
    $direction = (string) ($input['direction'] ?? '');

    if ($bindingId <= 0 || !in_array($direction, ['up', 'down'], true)) {
        return;
    }

    $bindings = $model['bindings'] ?? [];
    $index = null;
    $slotBindings = [];

    foreach ($bindings as $binding) {
        if (($binding['slot_key'] ?? null) === null) {
            continue;
        }

        $slotBindings[(string) $binding['slot_key']][] = $binding;
    }

    foreach ($bindings as $position => $binding) {
        if ((int) $binding['id'] === $bindingId) {
            $index = $position;
            break;
        }
    }

    if ($index === null) {
        return;
    }

    $current = $bindings[$index];
    $currentSlotBindings = $slotBindings[(string) $current['slot_key']] ?? [];
    $slotIndex = null;

    foreach ($currentSlotBindings as $position => $binding) {
        if ((int) $binding['id'] === $bindingId) {
            $slotIndex = $position;
            break;
        }
    }

    if ($slotIndex === null) {
        return;
    }

    $targetIndex = $direction === 'up' ? $slotIndex - 1 : $slotIndex + 1;

    if (!isset($currentSlotBindings[$targetIndex])) {
        return;
    }

    $target = $currentSlotBindings[$targetIndex];
    $currentSortOrder = (int) $current['sort_order'];
    $targetSortOrder = (int) $target['sort_order'];

    $pdo->beginTransaction();

    try {
        $updateCurrent = $pdo->prepare(
            'UPDATE sandbox_bindings
            SET sort_order = :sort_order
            WHERE id = :id
              AND sandbox_model_version_id = :sandbox_model_version_id'
        );
        $updateCurrent->execute([
            'sort_order' => $targetSortOrder,
            'id' => $current['id'],
            'sandbox_model_version_id' => $model['version']['id'],
        ]);

        $updateTarget = $pdo->prepare(
            'UPDATE sandbox_bindings
            SET sort_order = :sort_order
            WHERE id = :id
              AND sandbox_model_version_id = :sandbox_model_version_id'
        );
        $updateTarget->execute([
            'sort_order' => $currentSortOrder,
            'id' => $target['id'],
            'sandbox_model_version_id' => $model['version']['id'],
        ]);

        $pdo->commit();
    } catch (Throwable $exception) {
        $pdo->rollBack();
    }
}

function reposition_sandbox_binding(PDO $pdo, string $modelKey, array $input): void
{
    $versionNo = !empty($input['version']) ? (int) $input['version'] : null;
    $model = fetch_sandbox_model_by_key($pdo, $modelKey, $versionNo);

    if ($model === null || !is_array($model['version'] ?? null)) {
        return;
    }

    $versionId = (int) $model['version']['id'];
    $bindingId = (int) ($input['binding_id'] ?? 0);
    $targetSlot = (string) ($input['target_slot'] ?? '');
    $beforeBindingId = (int) ($input['before_binding_id'] ?? 0);
    $availableSlots = $model['slots'] ?? [];

    if ($bindingId <= 0 || $targetSlot === '' || !in_array($targetSlot, $availableSlots, true)) {
        return;
    }

    $currentBinding = null;
    $targetBinding = null;

    foreach (($model['bindings'] ?? []) as $binding) {
        if ((int) $binding['id'] === $bindingId) {
            $currentBinding = $binding;
        }

        if ($beforeBindingId > 0 && (int) $binding['id'] === $beforeBindingId) {
            $targetBinding = $binding;
        }
    }

    if (!is_array($currentBinding)) {
        return;
    }

    if ($beforeBindingId > 0 && (!is_array($targetBinding) || (string) ($targetBinding['slot_key'] ?? '') !== $targetSlot)) {
        return;
    }

    $targetBindings = [];

    foreach (($model['bindings'] ?? []) as $binding) {
        if ((int) $binding['id'] === $bindingId) {
            continue;
        }

        if ((string) ($binding['slot_key'] ?? '') === $targetSlot) {
            $targetBindings[] = $binding;
        }
    }

    $insertIndex = count($targetBindings);

    if ($beforeBindingId > 0) {
        foreach ($targetBindings as $index => $binding) {
            if ((int) $binding['id'] === $beforeBindingId) {
                $insertIndex = $index;
                break;
            }
        }
    }

    $pdo->beginTransaction();

    try {
        $moveStatement = $pdo->prepare(
            'UPDATE sandbox_bindings
            SET slot_key = :slot_key,
                sort_order = :sort_order
            WHERE id = :id
              AND sandbox_model_version_id = :sandbox_model_version_id'
        );

        $moveStatement->execute([
            'slot_key' => $targetSlot,
            'sort_order' => (($insertIndex + 1) * 10) - 5,
            'id' => $bindingId,
            'sandbox_model_version_id' => $versionId,
        ]);

        resequence_sandbox_bindings($pdo, $versionId, (string) $currentBinding['slot_key']);
        resequence_sandbox_bindings($pdo, $versionId, $targetSlot);

        update_sandbox_version_state($pdo, $modelKey, [
            'version' => $versionNo,
            'selected_slot' => $targetSlot,
            'selected_binding_id' => $bindingId,
        ]);

        $pdo->commit();
    } catch (Throwable $exception) {
        $pdo->rollBack();
    }
}

function update_sandbox_binding_properties(PDO $pdo, string $modelKey, array $input): void
{
    $versionNo = !empty($input['version']) ? (int) $input['version'] : null;
    $model = fetch_sandbox_model_by_key($pdo, $modelKey, $versionNo);

    if ($model === null || !is_array($model['version'] ?? null)) {
        return;
    }

    $bindingId = (int) ($input['binding_id'] ?? 0);
    if ($bindingId <= 0) {
        return;
    }

    $currentBinding = null;

    foreach (($model['bindings'] ?? []) as $binding) {
        if ((int) ($binding['id'] ?? 0) === $bindingId) {
            $currentBinding = $binding;
            break;
        }
    }

    if (!is_array($currentBinding)) {
        return;
    }

    $widthValue = is_numeric($input['width_value'] ?? null) ? (float) $input['width_value'] : 100.0;
    $widthUnit = (string) ($input['width_unit'] ?? 'percent');
    $heightValue = is_numeric($input['height_value'] ?? null) ? (float) $input['height_value'] : null;
    $heightUnit = (string) ($input['height_unit'] ?? 'percent');
    $minWidthValue = is_numeric($input['min_width_value'] ?? null) ? (float) $input['min_width_value'] : null;
    $minWidthUnit = (string) ($input['min_width_unit'] ?? 'px');
    $maxWidthValue = is_numeric($input['max_width_value'] ?? null) ? (float) $input['max_width_value'] : null;
    $maxWidthUnit = (string) ($input['max_width_unit'] ?? 'percent');
    $paddingValue = is_numeric($input['padding_value'] ?? null) ? (float) $input['padding_value'] : null;
    $paddingUnit = (string) ($input['padding_unit'] ?? 'px');
    $gapValue = is_numeric($input['gap_value'] ?? null) ? (float) $input['gap_value'] : null;
    $gapUnit = (string) ($input['gap_unit'] ?? 'px');
    $borderRadiusValue = is_numeric($input['border_radius_value'] ?? null) ? (float) $input['border_radius_value'] : null;
    $borderRadiusUnit = (string) ($input['border_radius_unit'] ?? 'px');
    $backgroundMode = (string) ($input['background_mode'] ?? 'none');
    $backgroundColor = trim((string) ($input['background_color'] ?? ''));
    $backgroundGradientFrom = trim((string) ($input['background_gradient_from'] ?? ''));
    $backgroundGradientTo = trim((string) ($input['background_gradient_to'] ?? ''));
    $backgroundGradientAngle = is_numeric($input['background_gradient_angle'] ?? null) ? (float) $input['background_gradient_angle'] : null;
    $backgroundImageUrl = trim((string) ($input['background_image_url'] ?? ''));
    $backgroundPosition = trim((string) ($input['background_position'] ?? 'center center'));
    $backgroundSize = (string) ($input['background_size'] ?? 'cover');
    $backgroundRepeat = (string) ($input['background_repeat'] ?? 'no-repeat');
    $backgroundAttachment = (string) ($input['background_attachment'] ?? 'scroll');
    $backgroundBlendMode = (string) ($input['background_blend_mode'] ?? 'normal');
    $previewMediaUrl = trim((string) ($input['preview_media_url'] ?? ''));
    $mediaFitMode = (string) ($input['media_fit_mode'] ?? 'cover');
    $mediaRatio = (string) ($input['media_ratio'] ?? 'auto');
    $mediaFocusX = is_numeric($input['media_focus_x'] ?? null) ? (float) $input['media_focus_x'] : 50.0;
    $mediaFocusY = is_numeric($input['media_focus_y'] ?? null) ? (float) $input['media_focus_y'] : 50.0;
    $alignment = (string) ($input['alignment'] ?? 'start');
    $fallbackValue = (string) ($input['fallback_value'] ?? '');
    $visibilityMode = (string) ($input['visibility_mode'] ?? 'always');

    if (!in_array($widthUnit, sandbox_allowed_measure_units(), true)) {
        $widthUnit = 'percent';
    }

    if (!in_array($heightUnit, sandbox_allowed_measure_units(), true)) {
        $heightUnit = 'percent';
    }

    if (!in_array($minWidthUnit, sandbox_allowed_measure_units(), true)) {
        $minWidthUnit = 'px';
    }

    if (!in_array($maxWidthUnit, sandbox_allowed_measure_units(), true)) {
        $maxWidthUnit = 'percent';
    }

    if (!in_array($paddingUnit, sandbox_allowed_measure_units(), true)) {
        $paddingUnit = 'px';
    }

    if (!in_array($gapUnit, sandbox_allowed_measure_units(), true)) {
        $gapUnit = 'px';
    }

    if (!in_array($borderRadiusUnit, sandbox_allowed_measure_units(), true)) {
        $borderRadiusUnit = 'px';
    }

    if (!in_array($backgroundMode, sandbox_allowed_background_modes(), true)) {
        $backgroundMode = 'none';
    }

    if (!in_array($backgroundSize, sandbox_allowed_background_sizes(), true)) {
        $backgroundSize = 'cover';
    }

    if (!in_array($backgroundRepeat, sandbox_allowed_background_repeats(), true)) {
        $backgroundRepeat = 'no-repeat';
    }

    if (!in_array($backgroundAttachment, sandbox_allowed_background_attachments(), true)) {
        $backgroundAttachment = 'scroll';
    }

    if (!in_array($backgroundBlendMode, sandbox_allowed_background_blends(), true)) {
        $backgroundBlendMode = 'normal';
    }

    if (!in_array($mediaFitMode, sandbox_allowed_media_fit_modes(), true)) {
        $mediaFitMode = 'cover';
    }

    if (!in_array($mediaRatio, sandbox_allowed_media_ratios(), true)) {
        $mediaRatio = 'auto';
    }

    $mediaFocusX = max(0.0, min(100.0, $mediaFocusX));
    $mediaFocusY = max(0.0, min(100.0, $mediaFocusY));

    if (!in_array($alignment, sandbox_allowed_alignments(), true)) {
        $alignment = 'start';
    }

    if (!in_array($visibilityMode, ['always', 'conditional', 'hidden'], true)) {
        $visibilityMode = 'always';
    }

    $layoutConfig = [
        'min_width_value' => $minWidthValue,
        'min_width_unit' => $minWidthValue !== null ? $minWidthUnit : null,
        'max_width_value' => $maxWidthValue,
        'max_width_unit' => $maxWidthValue !== null ? $maxWidthUnit : null,
        'padding_value' => $paddingValue,
        'padding_unit' => $paddingValue !== null ? $paddingUnit : null,
        'gap_value' => $gapValue,
        'gap_unit' => $gapValue !== null ? $gapUnit : null,
        'border_radius_value' => $borderRadiusValue,
        'border_radius_unit' => $borderRadiusValue !== null ? $borderRadiusUnit : null,
        'background_mode' => $backgroundMode,
        'background_color' => $backgroundColor,
        'background_gradient_from' => $backgroundGradientFrom,
        'background_gradient_to' => $backgroundGradientTo,
        'background_gradient_angle' => $backgroundGradientAngle,
        'background_image_url' => $backgroundImageUrl,
        'background_position' => $backgroundPosition,
        'background_size' => $backgroundSize,
        'background_repeat' => $backgroundRepeat,
        'background_attachment' => $backgroundAttachment,
        'background_blend_mode' => $backgroundBlendMode,
    ];

    $existingMediaConfig = is_array($currentBinding['media_config_json']['media'] ?? null)
        ? $currentBinding['media_config_json']['media']
        : [];

    $mediaConfig = [
        'preview_media_url' => $previewMediaUrl,
        'fit_mode' => $mediaFitMode,
        'ratio' => $mediaRatio,
        'focus_x' => $mediaFocusX,
        'focus_y' => $mediaFocusY,
    ] + $existingMediaConfig;

    $mediaConfig['preview_media_url'] = $previewMediaUrl;
    $mediaConfig['fit_mode'] = $mediaFitMode;
    $mediaConfig['ratio'] = $mediaRatio;
    $mediaConfig['focus_x'] = $mediaFocusX;
    $mediaConfig['focus_y'] = $mediaFocusY;

    $statement = $pdo->prepare(
        'UPDATE sandbox_bindings
        SET width_value = :width_value,
            width_unit = :width_unit,
            height_value = :height_value,
            height_unit = :height_unit,
            alignment = :alignment,
            fallback_value = :fallback_value,
            visibility_rule = :visibility_rule,
            media_config_json = :media_config_json
        WHERE id = :id
          AND sandbox_model_version_id = :sandbox_model_version_id'
    );

    $statement->execute([
        'width_value' => $widthValue,
        'width_unit' => $widthUnit,
        'height_value' => $heightValue,
        'height_unit' => $heightUnit,
        'alignment' => $alignment,
        'fallback_value' => $fallbackValue,
        'visibility_rule' => json_encode(['mode' => $visibilityMode], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        'media_config_json' => json_encode([
            'layout' => $layoutConfig,
            'media' => $mediaConfig,
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        'id' => $bindingId,
        'sandbox_model_version_id' => $model['version']['id'],
    ]);
}

function fetch_sandbox_model_by_key(PDO $pdo, string $modelKey, ?int $versionNo = null, ?string $selectedSourceKey = null): ?array
{
    $statement = $pdo->prepare(
        'SELECT *
        FROM sandbox_models
        WHERE model_key = :model_key
        LIMIT 1'
    );

    $statement->execute([
        'model_key' => $modelKey,
    ]);

    $model = $statement->fetch();

    if (!is_array($model)) {
        return null;
    }

    $model['notes'] = (string) ($model['notes'] ?? '');

    if ($versionNo !== null && $versionNo > 0) {
        $versionStatement = $pdo->prepare(
            'SELECT *
            FROM sandbox_model_versions
            WHERE sandbox_model_id = :sandbox_model_id
              AND version_no = :version_no
            LIMIT 1'
        );

        $versionStatement->execute([
            'sandbox_model_id' => $model['id'],
            'version_no' => $versionNo,
        ]);
    } else {
        $versionStatement = $pdo->prepare(
            'SELECT *
            FROM sandbox_model_versions
            WHERE sandbox_model_id = :sandbox_model_id
            ORDER BY version_no DESC
            LIMIT 1'
        );

        $versionStatement->execute([
            'sandbox_model_id' => $model['id'],
        ]);
    }

    $version = $versionStatement->fetch();

    if (is_array($version)) {
        $version['structure_json'] = decode_json_column($version['structure_json'] ?? null);
        $version['style_json'] = sandbox_normalize_style_profile(decode_json_column($version['style_json'] ?? null));
        $version['data_contract_json'] = decode_json_column($version['data_contract_json'] ?? null);
        $version['preview_json'] = decode_json_column($version['preview_json'] ?? null);
        $version['structure_json']['frame'] = sandbox_normalize_frame_slots($version['structure_json']['frame'] ?? []);
        $version['structure_json']['slot_settings'] = sandbox_normalize_all_slot_settings(
            is_array($version['structure_json']['slot_settings'] ?? null) ? $version['structure_json']['slot_settings'] : [],
            $version['structure_json']['frame']
        );
        $version['preview_json'] = sandbox_merge_preview_placeholders(
            $version['preview_json'],
            $version['structure_json']['frame']
        );

        if (empty($version['structure_json']['selected_slot']) || !in_array((string) $version['structure_json']['selected_slot'], $version['structure_json']['frame'], true)) {
            $version['structure_json']['selected_slot'] = $version['structure_json']['frame'][0] ?? 'header';
        }

        if (!empty($version['structure_json']['selected_frame']) && !in_array((string) $version['structure_json']['selected_frame'], ['header', 'footer'], true)) {
            unset($version['structure_json']['selected_frame']);
        }
    }

    $bindingsStatement = $pdo->prepare(
        'SELECT *
        FROM sandbox_bindings
        WHERE sandbox_model_version_id = :sandbox_model_version_id
        ORDER BY slot_key ASC, sort_order ASC, id ASC'
    );

    $bindings = [];

    if (is_array($version)) {
        $bindingsStatement->execute([
            'sandbox_model_version_id' => $version['id'],
        ]);

        foreach ($bindingsStatement->fetchAll() as $binding) {
            $binding['visibility_rule'] = decode_json_column($binding['visibility_rule'] ?? null);
            $binding['media_config_json'] = decode_json_column($binding['media_config_json'] ?? null);
            $bindings[] = $binding;
        }
    }

    $model['version'] = $version;
    $dataSources = fetch_bindable_data_sources($pdo);

    if ($selectedSourceKey === null || find_data_source_by_key($dataSources, $selectedSourceKey) === null) {
        $selectedSourceKey = $dataSources[0]['source_key'] ?? null;
    }

    $model['bindings'] = $bindings;
    $model['model_list'] = fetch_sandbox_model_list($pdo);
    $model['data_sources'] = $dataSources;
    $model['selected_source_key'] = $selectedSourceKey;
    $model['selected_source'] = $selectedSourceKey !== null ? find_data_source_by_key($dataSources, $selectedSourceKey) : null;
    $model['versions'] = fetch_sandbox_versions($pdo, (int) $model['id']);
    $model['slots'] = $version['structure_json']['frame'] ?? [];
    $model['style_profile'] = $version['style_json'] ?? [];
    $model['selected_slot'] = $version['structure_json']['selected_slot'] ?? ($model['slots'][0] ?? null);
    $model['selected_binding_id'] = $version['structure_json']['selected_binding_id'] ?? null;
    $model['selected_binding_focus'] = $version['structure_json']['selected_binding_focus'] ?? 'binding';
    $model['selected_frame'] = $version['structure_json']['selected_frame'] ?? null;
    $model['slot_settings'] = $version['structure_json']['slot_settings'] ?? [];
    $model['selected_slot_settings'] = $model['selected_slot'] !== null
        ? ($model['slot_settings'][$model['selected_slot']] ?? sandbox_default_slot_settings((string) $model['selected_slot']))
        : null;
    $model['selected_binding'] = null;

    foreach ($bindings as $binding) {
        if ($model['selected_binding_id'] !== null && (int) $binding['id'] === (int) $model['selected_binding_id']) {
            $model['selected_binding'] = $binding;
            $model['selected_slot'] = $binding['slot_key'];
            break;
        }
    }

    if (!in_array((string) ($model['selected_binding_focus'] ?? 'binding'), ['binding', 'media'], true)) {
        $model['selected_binding_focus'] = 'binding';
    }

    if ($model['selected_slot'] !== null) {
        $model['selected_slot_settings'] = $model['slot_settings'][$model['selected_slot']] ?? sandbox_default_slot_settings((string) $model['selected_slot']);
    }

    $styleSource = resolve_sandbox_style_target($pdo, $model, $versionNo);
    if ($styleSource !== null && is_array($styleSource['version'] ?? null)) {
        $model['style_profile'] = $styleSource['version']['style_json'] ?? $model['style_profile'];
        $model['style_source_model_key'] = $styleSource['model_key'] ?? $model['model_key'];
        $model['style_source_model_type'] = $styleSource['model_type'] ?? $model['model_type'];
    } else {
        $model['style_source_model_key'] = $model['model_key'];
        $model['style_source_model_type'] = $model['model_type'];
    }

    return $model;
}

function fetch_sandbox_live_style_profile_by_template_id(PDO $pdo, int $templateId): array
{
    if ($templateId <= 0) {
        return [];
    }

    $statement = $pdo->prepare(
        'SELECT smv.style_json
        FROM sandbox_models sm
        INNER JOIN sandbox_model_versions smv ON smv.sandbox_model_id = sm.id
        WHERE sm.linked_template_id = :linked_template_id
          AND sm.model_type = :model_type
        ORDER BY smv.version_no DESC, smv.id DESC
        LIMIT 1'
    );

    $statement->execute([
        'linked_template_id' => $templateId,
        'model_type' => 'template',
    ]);

    $styleJson = $statement->fetchColumn();

    if (!is_string($styleJson) || $styleJson === '') {
        return [];
    }

    return sandbox_normalize_style_profile(decode_json_column($styleJson));
}
