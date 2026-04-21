<?php

declare(strict_types=1);

function group_blocks_by_slot(array $blocks): array
{
    $grouped = [];

    foreach ($blocks as $block) {
        $slotKey = (string) ($block['slot_key'] ?? 'main');
        $grouped[$slotKey] ??= [];
        $grouped[$slotKey][] = $block;
    }

    return $grouped;
}

function build_skin_variables(array $page): array
{
    $tokens = $page['skin']['tokens'] ?? [];
    $typography = $page['skin']['typography'] ?? [];

    return [
        '--bg' => (string) ($tokens['background'] ?? '#111111'),
        '--panel' => (string) ($tokens['surface'] ?? '#1b1b1b'),
        '--ink' => (string) ($tokens['text'] ?? '#f5f1e8'),
        '--muted' => (string) ($tokens['muted'] ?? 'rgba(245, 241, 232, 0.72)'),
        '--line' => (string) ($tokens['border'] ?? '#353535'),
        '--accent' => (string) ($tokens['accent'] ?? '#c14d2d'),
        '--accent-strong' => (string) ($tokens['accent_strong'] ?? '#df6845'),
        '--title-font' => (string) ($typography['title_font'] ?? 'Georgia'),
        '--body-font' => (string) ($typography['body_font'] ?? 'Georgia'),
    ];
}

function render_skin_variables(array $variables): string
{
    $lines = [];

    foreach ($variables as $name => $value) {
        $lines[] = sprintf('%s: %s;', $name, $value);
    }

    return implode(' ', $lines);
}
