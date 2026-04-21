<?php

declare(strict_types=1);

$pageTitle = $page['seo_json']['title'] ?? $page['title'];
$pageDescription = $page['seo_json']['description'] ?? $page['summary'] ?? '';
$canonicalUrl = rtrim($app['links']['public_url'], '/') . ($page['canonical_path'] ?? '');
$skinVariables = build_skin_variables($page);
$frame = $page['template']['structure']['frame'] ?? [];
$slots = $page['template']['slots']['slots'] ?? [];
$headerMenu = $app['menus']['header'] ?? null;
$brandName = $app['settings']['system']['brand_name']['value'] ?? $page['title'];
$basePath = $app['request']['base_path'] ?: '';
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars((string) $pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
    <?php if ($pageDescription !== ''): ?>
        <meta name="description" content="<?= htmlspecialchars((string) $pageDescription, ENT_QUOTES, 'UTF-8') ?>">
    <?php endif; ?>
    <link rel="canonical" href="<?= htmlspecialchars($canonicalUrl, ENT_QUOTES, 'UTF-8') ?>">
    <link rel="stylesheet" href="<?= htmlspecialchars(($app['request']['base_path'] ?: '') . '/assets/css/site.css', ENT_QUOTES, 'UTF-8') ?>">
    <style>:root { <?= htmlspecialchars(render_skin_variables($skinVariables), ENT_QUOTES, 'UTF-8') ?> }</style>
</head>
<body>
    <?php if (is_array($headerMenu) && !empty($headerMenu['items'])): ?>
        <header class="site-header">
            <div class="site-header__inner">
                <a class="site-brand" href="<?= htmlspecialchars($basePath . '/kr-world', ENT_QUOTES, 'UTF-8') ?>">
                    <?= htmlspecialchars((string) $brandName, ENT_QUOTES, 'UTF-8') ?>
                </a>
                <nav class="site-nav" aria-label="Main navigation">
                    <?php foreach ($headerMenu['items'] as $item): ?>
                        <a class="site-nav__link" href="<?= htmlspecialchars($basePath . $item['resolved_url'], ENT_QUOTES, 'UTF-8') ?>">
                            <?= htmlspecialchars((string) $item['label'], ENT_QUOTES, 'UTF-8') ?>
                        </a>
                    <?php endforeach; ?>
                </nav>
            </div>
        </header>
    <?php endif; ?>
    <main class="page-shell">
        <?php foreach ($frame as $slotKey): ?>
            <?php if (!in_array($slotKey, $slots, true)) { continue; } ?>
            <?php $slotBlocks = $page['blocks_by_slot'][$slotKey] ?? []; ?>
            <?php if ($slotBlocks === []) { continue; } ?>
            <div class="slot slot-<?= htmlspecialchars((string) $slotKey, ENT_QUOTES, 'UTF-8') ?>">
                <?php foreach ($slotBlocks as $block): ?>
                    <?php
                    $moduleFile = __DIR__ . '/modules/' . ($block['module_key'] ?? '') . '.php';
                    if (!is_file($moduleFile)) {
                        $moduleFile = __DIR__ . '/modules/fallback.php';
                    }
                    require $moduleFile;
                    ?>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </main>
</body>
</html>
