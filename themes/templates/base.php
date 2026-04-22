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
$liveSandboxStyle = fetch_sandbox_live_style_profile_by_template_id($app['db'], (int) ($page['template_id'] ?? 0));
$renderMeasure = static function (mixed $value, ?string $unit): ?string {
    if ($value === null || $value === '') {
        return null;
    }

    $numeric = is_numeric($value) ? (float) $value : null;
    if ($numeric === null) {
        return null;
    }

    $normalized = fmod($numeric, 1.0) === 0.0 ? (string) (int) $numeric : rtrim(rtrim((string) $numeric, '0'), '.');
    $suffix = $unit === 'px' ? 'px' : '%';

    return $normalized . $suffix;
};
$pageCssVars = [];
$pageMaxWidth = $renderMeasure($liveSandboxStyle['page_max_width_value'] ?? null, $liveSandboxStyle['page_max_width_unit'] ?? null);
$pagePadding = $renderMeasure($liveSandboxStyle['page_padding_value'] ?? null, $liveSandboxStyle['page_padding_unit'] ?? null);
$pageSlotGap = $renderMeasure($liveSandboxStyle['page_slot_gap_value'] ?? null, $liveSandboxStyle['page_slot_gap_unit'] ?? null);
$headerHeight = $renderMeasure($liveSandboxStyle['header_height_value'] ?? null, $liveSandboxStyle['header_height_unit'] ?? null);
$headerPadding = $renderMeasure($liveSandboxStyle['header_padding_value'] ?? null, $liveSandboxStyle['header_padding_unit'] ?? null);
$headerGap = $renderMeasure($liveSandboxStyle['header_gap_value'] ?? null, $liveSandboxStyle['header_gap_unit'] ?? null);
$footerHeight = $renderMeasure($liveSandboxStyle['footer_height_value'] ?? null, $liveSandboxStyle['footer_height_unit'] ?? null);
$footerPadding = $renderMeasure($liveSandboxStyle['footer_padding_value'] ?? null, $liveSandboxStyle['footer_padding_unit'] ?? null);
$footerGap = $renderMeasure($liveSandboxStyle['footer_gap_value'] ?? null, $liveSandboxStyle['footer_gap_unit'] ?? null);
$headerVisibilityMode = (string) ($liveSandboxStyle['header_visibility_mode'] ?? 'visible');
$footerVisibilityMode = (string) ($liveSandboxStyle['footer_visibility_mode'] ?? 'visible');
$headerLayoutMode = (string) ($liveSandboxStyle['header_layout_mode'] ?? 'split');
$footerLayoutMode = (string) ($liveSandboxStyle['footer_layout_mode'] ?? 'split');
$liveBrandName = trim((string) ($liveSandboxStyle['header_brand_label'] ?? '')) ?: $brandName;
$liveHeaderLogoUrl = trim((string) ($liveSandboxStyle['header_logo_url'] ?? ''));
$liveFooterLabel = trim((string) ($liveSandboxStyle['footer_label'] ?? '')) ?: 'Footer';
$headerLogoWidth = $renderMeasure($liveSandboxStyle['header_logo_width_value'] ?? null, $liveSandboxStyle['header_logo_width_unit'] ?? null);
$headerLogoHeight = $renderMeasure($liveSandboxStyle['header_logo_height_value'] ?? null, $liveSandboxStyle['header_logo_height_unit'] ?? null);
$headerLogoMaxHeight = $renderMeasure($liveSandboxStyle['header_logo_max_height_value'] ?? null, $liveSandboxStyle['header_logo_max_height_unit'] ?? null);
$headerLogoScaleMode = (string) ($liveSandboxStyle['header_logo_scale_mode'] ?? 'contain');

if ($pageMaxWidth !== null) {
    $pageCssVars[] = '--page-shell-max-width: ' . $pageMaxWidth . ';';
}

if ($pagePadding !== null) {
    $pageCssVars[] = '--page-shell-padding: ' . $pagePadding . ';';
}

if ($pageSlotGap !== null) {
    $pageCssVars[] = '--page-slot-gap: ' . $pageSlotGap . ';';
}

if ($headerHeight !== null) {
    $pageCssVars[] = '--site-header-height: ' . $headerHeight . ';';
}

if ($headerPadding !== null) {
    $pageCssVars[] = '--site-header-padding: ' . $headerPadding . ';';
}

if ($headerGap !== null) {
    $pageCssVars[] = '--site-header-gap: ' . $headerGap . ';';
}

if ($headerLogoWidth !== null) {
    $pageCssVars[] = '--site-header-logo-width: ' . $headerLogoWidth . ';';
}

if ($headerLogoHeight !== null) {
    $pageCssVars[] = '--site-header-logo-height: ' . $headerLogoHeight . ';';
}

if ($headerLogoMaxHeight !== null) {
    $pageCssVars[] = '--site-header-logo-max-height: ' . $headerLogoMaxHeight . ';';
}

$pageCssVars[] = '--site-header-logo-fit: ' . $headerLogoScaleMode . ';';

if ($footerHeight !== null) {
    $pageCssVars[] = '--site-footer-height: ' . $footerHeight . ';';
}

if ($footerPadding !== null) {
    $pageCssVars[] = '--site-footer-padding: ' . $footerPadding . ';';
}

if ($footerGap !== null) {
    $pageCssVars[] = '--site-footer-gap: ' . $footerGap . ';';
}

$escapeCssUrl = static function (string $value): string {
    return str_replace(['\\', '"', "\n", "\r"], ['\\\\', '\"', '', ''], $value);
};

$buildBackgroundCss = static function (array $style, string $stylePrefix, string $varPrefix) use ($escapeCssUrl): array {
    $mode = (string) ($style[$stylePrefix . '_mode'] ?? 'none');
    $color = trim((string) ($style[$stylePrefix . '_color'] ?? ''));
    $gradientFrom = trim((string) ($style[$stylePrefix . '_gradient_from'] ?? ''));
    $gradientTo = trim((string) ($style[$stylePrefix . '_gradient_to'] ?? ''));
    $gradientAngle = is_numeric($style[$stylePrefix . '_gradient_angle'] ?? null) ? (float) $style[$stylePrefix . '_gradient_angle'] : 180.0;
    $imageUrl = trim((string) ($style[$stylePrefix . '_image_url'] ?? ''));
    $position = trim((string) ($style[$stylePrefix . '_position'] ?? 'center center'));
    $size = trim((string) ($style[$stylePrefix . '_size'] ?? 'cover'));
    $repeat = trim((string) ($style[$stylePrefix . '_repeat'] ?? 'no-repeat'));
    $attachment = trim((string) ($style[$stylePrefix . '_attachment'] ?? 'scroll'));
    $blend = trim((string) ($style[$stylePrefix . '_blend_mode'] ?? 'normal'));
    $vars = [];
    $layers = [];

    if ($color !== '') {
        $vars[] = '--' . $varPrefix . '-color: ' . $color . ';';
    }

    if (in_array($mode, ['gradient', 'gradient_image'], true)) {
        $layers[] = 'linear-gradient(' . $gradientAngle . 'deg, ' . ($gradientFrom !== '' ? $gradientFrom : '#161616') . ', ' . ($gradientTo !== '' ? $gradientTo : '#090909') . ')';
    }

    if (in_array($mode, ['image', 'gradient_image'], true) && $imageUrl !== '') {
        $layers[] = 'url("' . $escapeCssUrl($imageUrl) . '")';
    }

    if ($layers !== []) {
        $vars[] = '--' . $varPrefix . '-image: ' . implode(', ', $layers) . ';';
        $vars[] = '--' . $varPrefix . '-position: ' . $position . ';';
        $vars[] = '--' . $varPrefix . '-size: ' . $size . ';';
        $vars[] = '--' . $varPrefix . '-repeat: ' . $repeat . ';';
        $vars[] = '--' . $varPrefix . '-attachment: ' . $attachment . ';';
        $vars[] = '--' . $varPrefix . '-blend: ' . $blend . ';';
    } else {
        $vars[] = '--' . $varPrefix . '-image: none;';
    }

    return $vars;
};

$pageCssVars = array_merge($pageCssVars, $buildBackgroundCss($liveSandboxStyle, 'page_background', 'page-shell-bg'));
$pageCssVars = array_merge($pageCssVars, $buildBackgroundCss($liveSandboxStyle, 'header_background', 'site-header-bg'));
$pageCssVars = array_merge($pageCssVars, $buildBackgroundCss($liveSandboxStyle, 'footer_background', 'site-footer-bg'));
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
    <?php if ($pageCssVars !== []): ?>
        <style>:root { <?= htmlspecialchars(implode(' ', $pageCssVars), ENT_QUOTES, 'UTF-8') ?> }</style>
    <?php endif; ?>
</head>
<body>
    <?php if ($headerVisibilityMode !== 'hidden' && trim($liveBrandName) !== ''): ?>
        <header class="site-header site-header--<?= htmlspecialchars($headerLayoutMode, ENT_QUOTES, 'UTF-8') ?>">
            <div class="site-header__inner">
                <a class="site-brand" href="<?= htmlspecialchars($basePath . '/kr-world', ENT_QUOTES, 'UTF-8') ?>">
                    <?php if ($liveHeaderLogoUrl !== ''): ?>
                        <img
                            class="site-brand__logo"
                            src="<?= htmlspecialchars($liveHeaderLogoUrl, ENT_QUOTES, 'UTF-8') ?>"
                            alt="<?= htmlspecialchars((string) $liveBrandName, ENT_QUOTES, 'UTF-8') ?>"
                        >
                    <?php endif; ?>
                    <span class="site-brand__label"><?= htmlspecialchars((string) $liveBrandName, ENT_QUOTES, 'UTF-8') ?></span>
                </a>
            </div>
        </header>
    <?php endif; ?>
    <?php if (is_array($headerMenu) && !empty($headerMenu['items'])): ?>
        <nav class="site-navigation-slot" aria-label="Main navigation">
            <div class="site-navigation-slot__inner">
                <?php foreach ($headerMenu['items'] as $item): ?>
                    <a class="site-nav__link" href="<?= htmlspecialchars($basePath . $item['resolved_url'], ENT_QUOTES, 'UTF-8') ?>">
                        <?= htmlspecialchars((string) $item['label'], ENT_QUOTES, 'UTF-8') ?>
                    </a>
                <?php endforeach; ?>
                <span class="site-nav__crumb">Breadcrumb</span>
            </div>
        </nav>
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
    <nav class="site-footer-navigation-slot" aria-label="Footer navigation">
        <div class="site-footer-navigation-slot__inner">
            <a class="site-footer__link" href="<?= htmlspecialchars($basePath . '/kr-world', ENT_QUOTES, 'UTF-8') ?>">Home</a>
            <a class="site-footer__link" href="<?= htmlspecialchars($basePath . '/page?slug=chart-hub', ENT_QUOTES, 'UTF-8') ?>">Chart Hub</a>
            <a class="site-footer__link" href="<?= htmlspecialchars($basePath . '/event?slug=kr-world-night', ENT_QUOTES, 'UTF-8') ?>">Eventi</a>
            <span class="site-nav__crumb">Breadcrumb</span>
        </div>
    </nav>
    <?php if ($footerVisibilityMode !== 'hidden'): ?>
        <footer class="site-footer site-footer--<?= htmlspecialchars($footerLayoutMode, ENT_QUOTES, 'UTF-8') ?>">
            <div class="site-footer__inner">
                <div>
                    <strong class="site-footer__title"><?= htmlspecialchars((string) $liveFooterLabel, ENT_QUOTES, 'UTF-8') ?></strong>
                    <p class="site-footer__copy">Chiusura pagina, link di servizio e richiamo brand.</p>
                </div>
            </div>
        </footer>
    <?php endif; ?>
</body>
</html>
