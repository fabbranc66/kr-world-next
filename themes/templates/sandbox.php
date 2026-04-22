<?php

declare(strict_types=1);

$pageTitle = $sandbox['name'] ?? 'Sandbox';
$basePath = $app['request']['base_path'] ?: '';
$version = $sandbox['version'] ?? [];
$preview = $version['preview_json'] ?? [];
$contract = $version['data_contract_json'] ?? [];
$models = $sandbox['model_list'] ?? [];
$sources = $sandbox['data_sources'] ?? [];
$slots = $sandbox['slots'] ?? [];
$logoLibrary = fetch_sandbox_logo_library($basePath);
$styleProfile = $sandbox['style_profile'] ?? [];
$selectedSlot = $sandbox['selected_slot'] ?? null;
$selectedFrame = $sandbox['selected_frame'] ?? null;
$modelKey = $sandbox['model_key'] ?? 'homepage_lab';
$gridMode = $version['structure_json']['grid_mode'] ?? 'snap_soft';
$selectedBinding = $sandbox['selected_binding'] ?? null;
$selectedBindingId = $sandbox['selected_binding_id'] ?? null;
$selectedBindingFocus = $sandbox['selected_binding_focus'] ?? 'binding';
$versions = $sandbox['versions'] ?? [];
$currentVersionNo = $version['version_no'] ?? null;
$selectedSourceKey = $sandbox['selected_source_key'] ?? null;
$selectedSource = $sandbox['selected_source'] ?? null;
$selectedSlotSettings = $sandbox['selected_slot_settings'] ?? null;
$styleSourceModelKey = $sandbox['style_source_model_key'] ?? $modelKey;
$styleSourceModelType = $sandbox['style_source_model_type'] ?? ($sandbox['model_type'] ?? 'section');
$selectedFieldKey = $_GET['field_key'] ?? ($selectedSource['fields'][0]['field_key'] ?? null);
$selectedFieldLabel = null;
$bindingsBySlot = [];
$placeholdersBySlot = [];
$dataSourceLabelsById = [];
$headerMenu = $app['menus']['header'] ?? null;
$brandName = $app['settings']['system']['brand_name']['value'] ?? ($app['config']['app_name'] ?? 'KR World');
$displaySlotLabel = static function (?string $slotKey, ?array $slotSettings = null): string {
    if ($slotKey === null || $slotKey === '') {
        return 'Nessuno slot';
    }

    $label = trim((string) (($slotSettings['label'] ?? '')));
    if ($slotKey === 'header' && ($label === '' || strcasecmp($label, 'header') === 0)) {
        return 'Masthead';
    }
    if ($slotKey === 'navigation' && ($label === '' || strcasecmp($label, 'navigation') === 0)) {
        return 'Navigation';
    }
    if ($slotKey === 'footer_navigation' && ($label === '' || strcasecmp($label, 'footer_navigation') === 0)) {
        return 'Footer Navigation';
    }
    if ($slotKey === 'footer' && ($label === '' || strcasecmp($label, 'footer') === 0)) {
        return 'Postscript';
    }
    if ($label !== '') {
        return $label;
    }

    return match ($slotKey) {
        'header' => 'Masthead',
        'navigation' => 'Navigation',
        'footer_navigation' => 'Footer Navigation',
        'footer' => 'Postscript',
        default => ucwords(str_replace('_', ' ', $slotKey)),
    };
};

foreach (($sandbox['bindings'] ?? []) as $binding) {
    $bindingsBySlot[(string) $binding['slot_key']][] = $binding;
}

foreach ($sources as $source) {
    if (isset($source['id'])) {
        $dataSourceLabelsById[(int) $source['id']] = (string) ($source['label'] ?? $source['source_key'] ?? 'source');
    }
}

foreach (($preview['placeholders'] ?? []) as $placeholder) {
    $placeholderSlot = (string) ($placeholder['slot'] ?? '');

    if ($placeholderSlot === '') {
        continue;
    }

    $placeholdersBySlot[$placeholderSlot][] = $placeholder;
}

foreach (($selectedSource['fields'] ?? []) as $field) {
    if (($field['field_key'] ?? null) === $selectedFieldKey) {
        $selectedFieldLabel = $field['label'] ?? $field['field_key'];
        break;
    }
}

if ($selectedFieldLabel === null) {
    $selectedFieldLabel = $selectedSource['fields'][0]['label'] ?? 'New Binding';
}

$selectedSlotBindings = $selectedSlot !== null ? ($bindingsBySlot[$selectedSlot] ?? []) : [];
$bindingSelectionMode = $selectedBinding !== null && $selectedBindingFocus === 'media' && $bindingUsesMedia($selectedBinding) ? 'media' : 'binding';
$selectionMode = $selectedBinding !== null ? $bindingSelectionMode : ($selectedFrame !== null ? 'frame' : 'slot');
$selectionTitle = $selectedBinding !== null
    ? (string) ($selectedBinding['field_alias'] ?: $selectedBinding['field_key'] ?: 'Binding')
    : ($selectedFrame !== null ? ucfirst((string) $selectedFrame) : $displaySlotLabel($selectedSlot, $selectedSlotSettings));
$selectionSubtitle = $selectedBinding !== null
    ? ($bindingSelectionMode === 'media' ? 'Stai lavorando sull’immagine del componente selezionato' : 'Composizione della sezione selezionata')
    : ($selectedFrame !== null ? 'Cornice di sistema selezionata' : 'Composizione dello slot selezionato');
$selectionBindings = $selectedBinding !== null ? [$selectedBinding] : $selectedSlotBindings;
$selectionHelp = $selectedBinding !== null
    ? ($bindingSelectionMode === 'media' ? 'A destra trovi solo le opzioni immagine del componente selezionato.' : 'A destra trovi solo le opzioni del componente selezionato.')
    : ($selectedFrame !== null ? 'Stai lavorando sulla cornice della pagina, separata dagli slot del modello.' : 'Stai lavorando sullo slot intero. Qui trovi tutti i binding che lo compongono.');
$navigationBindings = $bindingsBySlot['navigation'] ?? [];
$navigationPlaceholders = $placeholdersBySlot['navigation'] ?? [];
$navigationSelected = $selectionMode === 'slot' && $selectedSlot === 'navigation';
$navigationHasBindingFocus = $selectedBinding !== null && (($selectedBinding['slot_key'] ?? null) === 'navigation');
$footerNavigationBindings = $bindingsBySlot['footer_navigation'] ?? [];
$footerNavigationPlaceholders = $placeholdersBySlot['footer_navigation'] ?? [];
$footerNavigationSelected = $selectionMode === 'slot' && $selectedSlot === 'footer_navigation';
$footerNavigationHasBindingFocus = $selectedBinding !== null && (($selectedBinding['slot_key'] ?? null) === 'footer_navigation');
$selectedSlotLabel = $displaySlotLabel($selectedSlot, $selectedSlotSettings);
$treeOpen = [
    'template' => true,
    'structure' => false,
    'components' => false,
    'media' => false,
    'configuration' => false,
];

if ($selectionMode === 'slot') {
    $treeOpen['template'] = false;
    $treeOpen['components'] = true;
} elseif ($selectionMode === 'frame') {
    $treeOpen['template'] = false;
    $treeOpen['components'] = true;
} elseif ($selectionMode === 'binding') {
    $treeOpen['components'] = true;
} elseif ($selectionMode === 'media') {
    $treeOpen['media'] = true;
}
$leftPanelFocus = match ($selectionMode) {
    'slot', 'frame', 'binding' => 'components',
    'media' => 'media',
    default => '',
};
$componentSuggestions = ['Text', 'Image', 'Button', 'Badge'];

if ($selectedFrame === 'header') {
    $componentSuggestions = ['Logo', 'Brand Name', 'Slogan', 'Context Label', 'Badge', 'Utility Link'];
} elseif ($selectedFrame === 'footer') {
    $componentSuggestions = ['Footer Label', 'Legal Text', 'Service Link', 'Badge'];
} elseif ($selectedSlot === 'navigation' || $selectedSlot === 'footer_navigation') {
    $componentSuggestions = ['Nav Link', 'Breadcrumb Item', 'CTA Link', 'Status Badge'];
} elseif ($selectedSlot === 'header') {
    $componentSuggestions = ['Kicker', 'Title', 'Subtitle', 'Image', 'Button'];
} elseif ($selectedSlot !== null) {
    $componentSuggestions = ['Title', 'Text', 'Image', 'Button', 'Badge', 'List'];
}
$componentBlueprints = [
    'Logo' => ['data_source_key' => 'media_assets', 'field_key' => 'file_path'],
    'Brand Name' => ['data_source_key' => 'content_items', 'field_key' => 'title'],
    'Slogan' => ['data_source_key' => 'content_items', 'field_key' => 'summary'],
    'Context Label' => ['data_source_key' => 'content_items', 'field_key' => 'title'],
    'Badge' => ['data_source_key' => 'content_items', 'field_key' => 'title'],
    'Utility Link' => ['data_source_key' => 'content_items', 'field_key' => 'title'],
    'Footer Label' => ['data_source_key' => 'content_items', 'field_key' => 'title'],
    'Legal Text' => ['data_source_key' => 'content_items', 'field_key' => 'summary'],
    'Service Link' => ['data_source_key' => 'content_items', 'field_key' => 'title'],
    'Nav Link' => ['data_source_key' => 'content_items', 'field_key' => 'title'],
    'Breadcrumb Item' => ['data_source_key' => 'content_items', 'field_key' => 'title'],
    'CTA Link' => ['data_source_key' => 'content_items', 'field_key' => 'title'],
    'Status Badge' => ['data_source_key' => 'content_items', 'field_key' => 'title'],
    'Kicker' => ['data_source_key' => 'content_items', 'field_key' => 'title'],
    'Title' => ['data_source_key' => 'content_items', 'field_key' => 'title'],
    'Subtitle' => ['data_source_key' => 'content_items', 'field_key' => 'summary'],
    'Image' => ['data_source_key' => 'media_assets', 'field_key' => 'file_path'],
    'Button' => ['data_source_key' => 'content_items', 'field_key' => 'title'],
    'Text' => ['data_source_key' => 'content_items', 'field_key' => 'summary'],
    'List' => ['data_source_key' => 'content_items', 'field_key' => 'summary'],
];
$modelsByType = [
    'template' => [],
    'section' => [],
    'skin' => [],
    'configuration' => [],
];

foreach ($models as $item) {
    $type = (string) ($item['model_type'] ?? 'section');
    if (!array_key_exists($type, $modelsByType)) {
        $modelsByType[$type] = [];
    }
    $modelsByType[$type][] = $item;
}

$buildSandboxUrl = static function (array $params) use ($basePath, $modelKey, $currentVersionNo, $selectedSourceKey): string {
    $query = array_filter([
        'model' => $params['model'] ?? $modelKey,
        'version' => $params['version'] ?? $currentVersionNo,
        'data_source_key' => $params['data_source_key'] ?? $selectedSourceKey,
        'action' => $params['action'] ?? null,
        'selected_slot' => $params['selected_slot'] ?? null,
        'selected_frame' => $params['selected_frame'] ?? null,
        'binding_id' => $params['binding_id'] ?? null,
        'selected_binding_focus' => $params['selected_binding_focus'] ?? null,
        'direction' => $params['direction'] ?? null,
        'target_slot' => $params['target_slot'] ?? null,
        'before_binding_id' => $params['before_binding_id'] ?? null,
        'grid_mode' => $params['grid_mode'] ?? null,
        'field_key' => $params['field_key'] ?? null,
        'source_preview' => $params['source_preview'] ?? null,
    ], static fn ($value) => $value !== null && $value !== '');

    return $basePath . '/sandbox' . ($query !== [] ? '?' . http_build_query($query) : '');
};

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

$escapeCssUrl = static function (string $value): string {
    return str_replace(['\\', '"', "\n", "\r"], ['\\\\', '\"', '', ''], $value);
};

$resolveBackgroundInlineStyle = static function (array $config, string $prefix = 'background_') use ($escapeCssUrl): string {
    $mode = (string) ($config[$prefix . 'mode'] ?? 'none');
    $color = trim((string) ($config[$prefix . 'color'] ?? ''));
    $gradientFrom = trim((string) ($config[$prefix . 'gradient_from'] ?? ''));
    $gradientTo = trim((string) ($config[$prefix . 'gradient_to'] ?? ''));
    $gradientAngle = is_numeric($config[$prefix . 'gradient_angle'] ?? null) ? (float) $config[$prefix . 'gradient_angle'] : 135.0;
    $imageUrl = trim((string) ($config[$prefix . 'image_url'] ?? ''));
    $position = trim((string) ($config[$prefix . 'position'] ?? 'center center'));
    $size = trim((string) ($config[$prefix . 'size'] ?? 'cover'));
    $repeat = trim((string) ($config[$prefix . 'repeat'] ?? 'no-repeat'));
    $attachment = trim((string) ($config[$prefix . 'attachment'] ?? 'scroll'));
    $blendMode = trim((string) ($config[$prefix . 'blend_mode'] ?? 'normal'));
    $styles = [];
    $layers = [];

    if ($color !== '') {
        $styles[] = 'background-color: ' . $color;
    }

    if (in_array($mode, ['gradient', 'gradient_image'], true)) {
        $layers[] = 'linear-gradient(' . $gradientAngle . 'deg, ' . ($gradientFrom !== '' ? $gradientFrom : '#161616') . ', ' . ($gradientTo !== '' ? $gradientTo : '#090909') . ')';
    }

    if (in_array($mode, ['image', 'gradient_image'], true) && $imageUrl !== '') {
        $layers[] = 'url("' . $escapeCssUrl($imageUrl) . '")';
    }

    if ($mode === 'solid' && $color === '') {
        $styles[] = 'background-color: #101010';
    }

    if ($layers !== []) {
        $styles[] = 'background-image: ' . implode(', ', $layers);
        $styles[] = 'background-position: ' . $position;
        $styles[] = 'background-size: ' . $size;
        $styles[] = 'background-repeat: ' . $repeat;
        $styles[] = 'background-attachment: ' . $attachment;
        $styles[] = 'background-blend-mode: ' . $blendMode;
    }

    return implode('; ', $styles);
};

$bindingUsesMedia = static function (array $binding): bool {
    $fieldKey = strtolower((string) ($binding['field_key'] ?? ''));
    $bindType = strtolower((string) ($binding['bind_type'] ?? ''));
    $media = $binding['media_config_json']['media'] ?? [];
    $previewMediaUrl = trim((string) ($media['preview_media_url'] ?? ''));

    if ($bindType === 'media' || $previewMediaUrl !== '') {
        return true;
    }

    foreach (['image', 'media', 'cover', 'poster', 'thumbnail', 'thumb', 'hero'] as $token) {
        if ($fieldKey !== '' && str_contains($fieldKey, $token)) {
            return true;
        }
    }

    return false;
};

$resolveBindingInlineStyle = static function (array $binding) use ($renderMeasure, $resolveBackgroundInlineStyle): string {
    $styles = [];
    $width = $renderMeasure($binding['width_value'] ?? null, $binding['width_unit'] ?? null);
    $height = $renderMeasure($binding['height_value'] ?? null, $binding['height_unit'] ?? null);
    $alignment = (string) ($binding['alignment'] ?? 'start');
    $layoutConfig = $binding['media_config_json']['layout'] ?? [];
    $minWidth = $renderMeasure($layoutConfig['min_width_value'] ?? null, $layoutConfig['min_width_unit'] ?? null);
    $maxWidth = $renderMeasure($layoutConfig['max_width_value'] ?? null, $layoutConfig['max_width_unit'] ?? null);
    $padding = $renderMeasure($layoutConfig['padding_value'] ?? null, $layoutConfig['padding_unit'] ?? null);
    $gap = $renderMeasure($layoutConfig['gap_value'] ?? null, $layoutConfig['gap_unit'] ?? null);

    if ($alignment === 'stretch') {
        $styles[] = 'align-self: stretch';
    } elseif ($alignment === 'center') {
        $styles[] = 'align-self: center';
    } elseif ($alignment === 'end') {
        $styles[] = 'align-self: flex-end';
    } else {
        $styles[] = 'align-self: flex-start';
    }

    if ($width !== null) {
        $styles[] = 'width: ' . $width;
    }

    if ($height !== null) {
        $styles[] = 'min-height: ' . $height;
    }

    if ($minWidth !== null) {
        $styles[] = 'min-width: ' . $minWidth;
    }

    if ($maxWidth !== null) {
        $styles[] = 'max-width: ' . $maxWidth;
    }

    if ($padding !== null) {
        $styles[] = 'padding: ' . $padding;
    }

    if ($gap !== null) {
        $styles[] = '--sandbox-binding-gap: ' . $gap;
    }

    if (!empty($layoutConfig['border_radius_value'])) {
        $radius = $renderMeasure($layoutConfig['border_radius_value'], $layoutConfig['border_radius_unit'] ?? null);
        if ($radius !== null) {
            $styles[] = 'border-radius: ' . $radius;
        }
    }

    $backgroundStyles = $resolveBackgroundInlineStyle($layoutConfig, 'background_');
    if ($backgroundStyles !== '') {
        $styles[] = $backgroundStyles;
    }

    return implode('; ', $styles);
};

$resolveBindingMediaStyle = static function (array $binding) use ($escapeCssUrl): string {
    $mediaConfig = $binding['media_config_json']['media'] ?? [];
    $previewMediaUrl = trim((string) ($mediaConfig['preview_media_url'] ?? ''));
    $fitMode = (string) ($mediaConfig['fit_mode'] ?? 'cover');
    $ratio = (string) ($mediaConfig['ratio'] ?? 'auto');
    $focusX = is_numeric($mediaConfig['focus_x'] ?? null) ? (float) $mediaConfig['focus_x'] : 50.0;
    $focusY = is_numeric($mediaConfig['focus_y'] ?? null) ? (float) $mediaConfig['focus_y'] : 50.0;
    $styles = [];

    if ($previewMediaUrl !== '') {
        $styles[] = 'background-image: url("' . $escapeCssUrl($previewMediaUrl) . '")';
    }

    $styles[] = 'background-size: ' . match ($fitMode) {
        'contain' => 'contain',
        'fill' => '100% 100%',
        default => 'cover',
    };
    $styles[] = 'background-position: ' . max(0.0, min(100.0, $focusX)) . '% ' . max(0.0, min(100.0, $focusY)) . '%';
    $styles[] = 'background-repeat: no-repeat';

    if ($ratio !== 'auto') {
        $styles[] = 'aspect-ratio: ' . $ratio;
    }

    return implode('; ', $styles);
};

$bodyInlineStyle = $resolveBackgroundInlineStyle($styleProfile, 'body_background_');

$resolvePageFrameInlineStyle = static function (array $styleProfile) use ($renderMeasure, $resolveBackgroundInlineStyle): string {
    $styles = [];
    $maxWidth = $renderMeasure($styleProfile['page_max_width_value'] ?? null, $styleProfile['page_max_width_unit'] ?? null);
    $padding = $renderMeasure($styleProfile['page_padding_value'] ?? null, $styleProfile['page_padding_unit'] ?? null);
    $slotGap = $renderMeasure($styleProfile['page_slot_gap_value'] ?? null, $styleProfile['page_slot_gap_unit'] ?? null);
    $background = $resolveBackgroundInlineStyle($styleProfile, 'page_background_');

    if ($maxWidth !== null) {
        $styles[] = 'max-width: ' . $maxWidth;
    }

    if ($padding !== null) {
        $styles[] = '--sandbox-page-padding: ' . $padding;
    }

    if ($slotGap !== null) {
        $styles[] = '--sandbox-page-slot-gap: ' . $slotGap;
    }

    if ($background !== '') {
        $styles[] = $background;
    }

    return implode('; ', $styles);
};

$pageFrameInlineStyle = $resolvePageFrameInlineStyle($styleProfile);
$headerBrandLabel = trim((string) ($styleProfile['header_brand_label'] ?? '')) ?: $brandName;
$headerLogoUrl = trim((string) ($styleProfile['header_logo_url'] ?? ''));
$logoLibraryUrls = array_map(static fn (array $item): string => (string) ($item['url'] ?? ''), $logoLibrary);
$headerLogoActualWidth = null;
$headerLogoActualHeight = null;

if ($headerLogoUrl !== '') {
    $publicPrefix = rtrim($basePath, '/') . '/assets/';
    $relativeAssetPath = null;

    if ($basePath !== '' && str_starts_with($headerLogoUrl, $publicPrefix)) {
        $relativeAssetPath = substr($headerLogoUrl, strlen(rtrim($basePath, '/')));
    } elseif (str_starts_with($headerLogoUrl, '/assets/')) {
        $relativeAssetPath = $headerLogoUrl;
    }

    if (is_string($relativeAssetPath) && $relativeAssetPath !== '') {
        $localLogoPath = dirname(__DIR__, 2) . '/public' . $relativeAssetPath;
        if (is_file($localLogoPath)) {
            $logoImageInfo = @getimagesize($localLogoPath);
            if (is_array($logoImageInfo) && isset($logoImageInfo[0], $logoImageInfo[1])) {
                $headerLogoActualWidth = (int) $logoImageInfo[0];
                $headerLogoActualHeight = (int) $logoImageInfo[1];
            }
        }
    }
}

$headerLogoWidthValue = $styleProfile['header_logo_width_value'] ?? null;
$headerLogoHeightValue = $styleProfile['header_logo_height_value'] ?? null;
$headerLogoMaxHeightValue = $styleProfile['header_logo_max_height_value'] ?? null;

if ($headerLogoActualWidth !== null && ($headerLogoWidthValue === null || (float) $headerLogoWidthValue === 120.0)) {
    $headerLogoWidthValue = $headerLogoActualWidth;
}

if ($headerLogoActualHeight !== null && ($headerLogoHeightValue === null || (float) $headerLogoHeightValue === 44.0)) {
    $headerLogoHeightValue = $headerLogoActualHeight;
}

if ($headerLogoActualHeight !== null && ($headerLogoMaxHeightValue === null || (float) $headerLogoMaxHeightValue === 52.0)) {
    $headerLogoMaxHeightValue = $headerLogoActualHeight;
}

$headerLogoSourceMode = 'web';
if ($headerLogoUrl !== '' && in_array($headerLogoUrl, $logoLibraryUrls, true)) {
    $headerLogoSourceMode = 'library';
}
$headerNavigationVisible = true;
$headerVisibilityMode = (string) ($styleProfile['header_visibility_mode'] ?? 'visible');
$headerLayoutMode = (string) ($styleProfile['header_layout_mode'] ?? 'split');
$footerLabel = trim((string) ($styleProfile['footer_label'] ?? '')) ?: 'Footer';
$footerNavigationVisible = true;
$footerLayoutMode = (string) ($styleProfile['footer_layout_mode'] ?? 'split');

$resolveHeaderInlineStyle = static function (array $styleProfile) use ($renderMeasure, $resolveBackgroundInlineStyle): string {
    $styles = [];
    $padding = $renderMeasure($styleProfile['header_padding_value'] ?? null, $styleProfile['header_padding_unit'] ?? null);
    $height = $renderMeasure($styleProfile['header_height_value'] ?? null, $styleProfile['header_height_unit'] ?? null);
    $gap = $renderMeasure($styleProfile['header_gap_value'] ?? null, $styleProfile['header_gap_unit'] ?? null);
    $background = $resolveBackgroundInlineStyle($styleProfile, 'header_background_');

    if ($padding !== null) {
        $styles[] = '--sandbox-header-padding: ' . $padding;
    }

    if ($height !== null) {
        $styles[] = '--sandbox-header-height: ' . $height;
    }

    if ($gap !== null) {
        $styles[] = '--sandbox-header-gap: ' . $gap;
    }

    if ($background !== '') {
        $styles[] = $background;
    }

    if (($styleProfile['header_visibility_mode'] ?? 'visible') === 'hidden') {
        $styles[] = 'opacity: 0.38';
        $styles[] = 'filter: saturate(0.55)';
    }

    return implode('; ', $styles);
};

$headerInlineStyle = $resolveHeaderInlineStyle($styleProfile);
$resolveHeaderLogoInlineStyle = static function (array $styleProfile) use ($renderMeasure): string {
    $styles = [];
    $width = $renderMeasure($styleProfile['header_logo_width_value'] ?? null, $styleProfile['header_logo_width_unit'] ?? null);
    $height = $renderMeasure($styleProfile['header_logo_height_value'] ?? null, $styleProfile['header_logo_height_unit'] ?? null);
    $maxHeight = $renderMeasure($styleProfile['header_logo_max_height_value'] ?? null, $styleProfile['header_logo_max_height_unit'] ?? null);
    $fit = (string) ($styleProfile['header_logo_scale_mode'] ?? 'contain');

    if ($width !== null) {
        $styles[] = 'width: ' . $width;
    }

    if ($height !== null) {
        $styles[] = 'height: ' . $height;
    }

    if ($maxHeight !== null) {
        $styles[] = 'max-height: ' . $maxHeight;
    }

    $styles[] = 'object-fit: ' . $fit;

    return implode('; ', $styles);
};
$headerLogoInlineStyle = $resolveHeaderLogoInlineStyle($styleProfile);

$resolveFooterInlineStyle = static function (array $styleProfile) use ($renderMeasure, $resolveBackgroundInlineStyle): string {
    $styles = [];
    $padding = $renderMeasure($styleProfile['footer_padding_value'] ?? null, $styleProfile['footer_padding_unit'] ?? null);
    $height = $renderMeasure($styleProfile['footer_height_value'] ?? null, $styleProfile['footer_height_unit'] ?? null);
    $gap = $renderMeasure($styleProfile['footer_gap_value'] ?? null, $styleProfile['footer_gap_unit'] ?? null);
    $background = $resolveBackgroundInlineStyle($styleProfile, 'footer_background_');

    if ($padding !== null) {
        $styles[] = '--sandbox-footer-padding: ' . $padding;
    }

    if ($height !== null) {
        $styles[] = '--sandbox-footer-height: ' . $height;
    }

    if ($gap !== null) {
        $styles[] = '--sandbox-footer-gap: ' . $gap;
    }

    if ($background !== '') {
        $styles[] = $background;
    }

    if (($styleProfile['footer_visibility_mode'] ?? 'visible') === 'hidden') {
        $styles[] = 'opacity: 0.38';
        $styles[] = 'filter: saturate(0.55)';
    }

    return implode('; ', $styles);
};

$footerInlineStyle = $resolveFooterInlineStyle($styleProfile);

$resolveSlotInlineStyle = static function (array $settings) use ($renderMeasure): string {
    $styles = [];
    $padding = $renderMeasure($settings['padding_value'] ?? null, $settings['padding_unit'] ?? null);
    $gap = $renderMeasure($settings['gap_value'] ?? null, $settings['gap_unit'] ?? null);

    if ($padding !== null) {
        $styles[] = '--sandbox-slot-padding: ' . $padding;
    }

    if ($gap !== null) {
        $styles[] = '--sandbox-slot-gap: ' . $gap;
    }

    if (($settings['visibility_mode'] ?? 'visible') === 'hidden') {
        $styles[] = 'opacity: 0.4';
    }

    return implode('; ', $styles);
};
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars((string) $pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="<?= htmlspecialchars($basePath . '/assets/css/system/base.css', ENT_QUOTES, 'UTF-8') ?>">
    <link rel="stylesheet" href="<?= htmlspecialchars($basePath . '/assets/css/sandbox/layout.css', ENT_QUOTES, 'UTF-8') ?>">
    <link rel="stylesheet" href="<?= htmlspecialchars($basePath . '/assets/css/sandbox/modules.css', ENT_QUOTES, 'UTF-8') ?>">
</head>
<body<?= $bodyInlineStyle !== '' ? ' style="' . htmlspecialchars($bodyInlineStyle, ENT_QUOTES, 'UTF-8') . '"' : '' ?>>
    <main class="sandbox-shell">
        <header class="sandbox-header">
            <p class="sandbox-kicker">Sandbox Project</p>
            <h1 class="sandbox-title"><?= htmlspecialchars((string) $pageTitle, ENT_QUOTES, 'UTF-8') ?></h1>
            <?php if (!empty($sandbox['notes'])): ?>
                <p class="sandbox-copy"><?= htmlspecialchars((string) $sandbox['notes'], ENT_QUOTES, 'UTF-8') ?></p>
            <?php endif; ?>
            <div class="sandbox-quick-guide">
                <span class="sandbox-field-chip">1. Pagina Globale = cornice e gap tra slot</span>
                <span class="sandbox-field-chip">2. Click su uno slot = opzioni sezione</span>
                <span class="sandbox-field-chip">3. Click su un componente = opzioni componente</span>
            </div>
        </header>

        <section class="sandbox-grid">
            <aside class="sandbox-panel" data-sandbox-left-panel data-sandbox-left-focus="<?= htmlspecialchars($leftPanelFocus, ENT_QUOTES, 'UTF-8') ?>">
                <p class="sandbox-kicker">Stai Modificando</p>
                <div class="sandbox-focus-card sandbox-focus-card--panel">
                    <p class="sandbox-focus-card__eyebrow"><?= htmlspecialchars($selectionMode === 'media' ? 'Immagine' : ($selectionMode === 'binding' ? 'Componente' : 'Slot'), ENT_QUOTES, 'UTF-8') ?></p>
                    <h3 class="sandbox-focus-card__title"><?= htmlspecialchars($selectionTitle, ENT_QUOTES, 'UTF-8') ?></h3>
                    <p class="sandbox-focus-card__hint"><?= htmlspecialchars($selectionMode === 'media' ? 'A destra trovi solo le opzioni immagine del componente selezionato.' : ($selectionMode === 'binding' ? 'A destra trovi solo le opzioni del componente selezionato.' : 'A destra trovi solo le opzioni dello slot selezionato.'), ENT_QUOTES, 'UTF-8') ?></p>
                </div>

                <div class="sandbox-tree">
                    <details class="sandbox-tree__group"<?= $treeOpen['template'] ? ' open' : '' ?>>
                        <summary class="sandbox-tree__summary">Template</summary>
                        <p class="sandbox-meta">Tipo: <?= htmlspecialchars((string) ($sandbox['model_type'] ?? ''), ENT_QUOTES, 'UTF-8') ?></p>
                        <p class="sandbox-meta">Stato: <?= htmlspecialchars((string) ($sandbox['status'] ?? ''), ENT_QUOTES, 'UTF-8') ?></p>
                        <p class="sandbox-meta">Versione: <?= htmlspecialchars((string) $currentVersionNo, ENT_QUOTES, 'UTF-8') ?></p>
                        <a class="sandbox-action sandbox-action--ghost" href="<?= htmlspecialchars($basePath . '/sandbox?model=' . urlencode((string) $modelKey) . '&version=' . urlencode((string) $currentVersionNo) . '&action=clone_version', ENT_QUOTES, 'UTF-8') ?>">
                            Nuova Versione
                        </a>
                        <ul class="sandbox-list">
                            <?php foreach ($versions as $item): ?>
                                <li>
                                    <a href="<?= htmlspecialchars($buildSandboxUrl(['version' => $item['version_no']]), ENT_QUOTES, 'UTF-8') ?>">
                                        v<?= htmlspecialchars((string) $item['version_no'], ENT_QUOTES, 'UTF-8') ?>
                                    </a>
                                    <?php if ((int) $currentVersionNo === (int) $item['version_no']): ?>
                                        <span class="sandbox-field-chip">active</span>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="sandbox-library">
                            <?php foreach (['template' => 'Template Pagina', 'section' => 'Sezioni', 'skin' => 'Skin', 'configuration' => 'Configurazioni'] as $type => $label): ?>
                                <?php if (($modelsByType[$type] ?? []) === []) { continue; } ?>
                                <div class="sandbox-library__group">
                                    <p class="sandbox-library__title"><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></p>
                                    <ul class="sandbox-list sandbox-list--plain">
                                        <?php foreach ($modelsByType[$type] as $item): ?>
                                            <li>
                                                <a href="<?= htmlspecialchars($buildSandboxUrl(['model' => $item['model_key'], 'version' => null]), ENT_QUOTES, 'UTF-8') ?>">
                                                    <?= htmlspecialchars((string) $item['name'], ENT_QUOTES, 'UTF-8') ?>
                                                </a>
                                                <?php if (($item['model_key'] ?? null) === $modelKey): ?>
                                                    <span class="sandbox-field-chip">active</span>
                                                <?php endif; ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </details>

                    <details class="sandbox-tree__group"<?= $treeOpen['structure'] ? ' open' : '' ?> data-sandbox-left-focus-target="structure">
                        <summary class="sandbox-tree__summary">Struttura</summary>
                        <ul class="sandbox-list">
                            <?php foreach ($slots as $slot): ?>
                                <li>
                                    <a href="<?= htmlspecialchars($buildSandboxUrl(['action' => 'update_state', 'selected_slot' => $slot]), ENT_QUOTES, 'UTF-8') ?>">
                                        <?= htmlspecialchars($displaySlotLabel((string) $slot, $sandbox['slot_settings'][$slot] ?? null), ENT_QUOTES, 'UTF-8') ?>
                                    </a>
                                    <?php if ($selectedSlot === $slot): ?>
                                        <span class="sandbox-field-chip">active</span>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                            <li>
                                <a href="<?= htmlspecialchars($buildSandboxUrl(['action' => 'update_state', 'selected_frame' => 'header']), ENT_QUOTES, 'UTF-8') ?>">Header Frame</a>
                                <?php if ($selectedFrame === 'header'): ?><span class="sandbox-field-chip">active</span><?php endif; ?>
                            </li>
                            <li>
                                <a href="<?= htmlspecialchars($buildSandboxUrl(['action' => 'update_state', 'selected_frame' => 'footer']), ENT_QUOTES, 'UTF-8') ?>">Footer Frame</a>
                                <?php if ($selectedFrame === 'footer'): ?><span class="sandbox-field-chip">active</span><?php endif; ?>
                            </li>
                        </ul>
                    </details>

                    <details class="sandbox-tree__group"<?= $treeOpen['components'] ? ' open' : '' ?> data-sandbox-left-focus-target="components">
                        <summary class="sandbox-tree__summary">Componenti</summary>
                        <ul class="sandbox-list">
                            <?php foreach ($componentSuggestions as $componentLabel): ?>
                                <?php $componentConfig = $componentBlueprints[$componentLabel] ?? null; ?>
                                <li>
                                    <?php if ($selectedSlot !== null && is_array($componentConfig)): ?>
                                        <a
                                            href="<?= htmlspecialchars($buildSandboxUrl([
                                                'action' => 'create_binding',
                                                'selected_slot' => $selectedSlot,
                                                'slot_key' => $selectedSlot,
                                                'data_source_key' => $componentConfig['data_source_key'],
                                                'field_key' => $componentConfig['field_key'],
                                                'field_alias' => $componentLabel,
                                                'allow_duplicate' => 1,
                                            ]), ENT_QUOTES, 'UTF-8') ?>"
                                        >
                                            <?= htmlspecialchars($componentLabel, ENT_QUOTES, 'UTF-8') ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="sandbox-meta"><?= htmlspecialchars($componentLabel, ENT_QUOTES, 'UTF-8') ?></span>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <?php if ($selectedSlot === null): ?>
                            <p class="sandbox-meta">Seleziona uno slot nella preview per inserire componenti da qui.</p>
                        <?php endif; ?>
                    </details>

                    <details class="sandbox-tree__group"<?= $treeOpen['media'] ? ' open' : '' ?> data-sandbox-left-focus-target="media">
                        <summary class="sandbox-tree__summary">Media</summary>
                        <ul class="sandbox-list">
                            <li><span class="sandbox-meta">Libreria locale</span></li>
                            <li><span class="sandbox-meta">Upload desktop</span></li>
                            <li><span class="sandbox-meta">URL web</span></li>
                        </ul>
                    </details>

                    <details class="sandbox-tree__group"<?= $treeOpen['configuration'] ? ' open' : '' ?> data-sandbox-left-focus-target="configuration">
                        <summary class="sandbox-tree__summary">Configurazione</summary>
                        <ul class="sandbox-list">
                            <li><a href="#page-global">Pagina Globale</a></li>
                            <li><span class="sandbox-meta"><?= $selectedFrame === 'header' ? 'Header' : ($selectedFrame === 'footer' ? 'Footer' : ($selectedSlot !== null ? $selectedSlotLabel : 'Selezione')) ?></span></li>
                            <li><a href="<?= htmlspecialchars($buildSandboxUrl(['action' => 'update_state', 'grid_mode' => 'snap_strong']), ENT_QUOTES, 'UTF-8') ?>">Grid: Strong</a></li>
                            <li><a href="<?= htmlspecialchars($buildSandboxUrl(['action' => 'update_state', 'grid_mode' => 'snap_soft']), ENT_QUOTES, 'UTF-8') ?>">Grid: Soft</a></li>
                            <li><a href="<?= htmlspecialchars($buildSandboxUrl(['action' => 'update_state', 'grid_mode' => 'snap_off']), ENT_QUOTES, 'UTF-8') ?>">Grid: Off</a></li>
                        </ul>
                    </details>

                    <details class="sandbox-tree__group">
                        <summary class="sandbox-tree__summary">Nuovo Modello</summary>
                        <form class="sandbox-form" method="get" action="<?= htmlspecialchars($basePath . '/sandbox', ENT_QUOTES, 'UTF-8') ?>">
                            <input type="hidden" name="action" value="create_model">
                            <label class="sandbox-form__label">
                                Nome
                                <input class="sandbox-form__control" type="text" name="new_model_name" placeholder="Live Hub Lab">
                            </label>
                            <label class="sandbox-form__label">
                                Chiave
                                <input class="sandbox-form__control" type="text" name="new_model_key" placeholder="live_hub_lab">
                            </label>
                            <label class="sandbox-form__label">
                                Tipo
                                <select name="new_model_type" class="sandbox-form__control">
                                    <?php foreach (['section', 'template', 'skin', 'configuration'] as $type): ?>
                                        <option value="<?= htmlspecialchars($type, ENT_QUOTES, 'UTF-8') ?>">
                                            <?= htmlspecialchars($type, ENT_QUOTES, 'UTF-8') ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </label>
                            <label class="sandbox-form__label">
                                Slot
                                <input class="sandbox-form__control" type="text" name="new_model_slots" value="header, navigation, hero, main, aside, footer_navigation, footer">
                            </label>
                            <button class="sandbox-action" type="submit">Crea Modello</button>
                        </form>
                    </details>
                </div>

                <details class="sandbox-tree__group"<?= in_array($selectionMode, ['slot', 'frame'], true) ? '' : ' open' ?>>
                    <summary class="sandbox-tree__summary" id="page-global">Pagina Globale</summary>
                    <form class="sandbox-form" method="get" action="<?= htmlspecialchars($basePath . '/sandbox', ENT_QUOTES, 'UTF-8') ?>" data-sandbox-preview-form="page">
                        <input type="hidden" name="model" value="<?= htmlspecialchars((string) $modelKey, ENT_QUOTES, 'UTF-8') ?>">
                        <input type="hidden" name="version" value="<?= htmlspecialchars((string) $currentVersionNo, ENT_QUOTES, 'UTF-8') ?>">
                        <input type="hidden" name="action" value="update_page_canvas">
                        <label class="sandbox-form__label">
                            Larghezza Pagina
                            <span class="sandbox-form__help">Ampiezza massima del contenitore che racchiude tutti gli slot.</span>
                            <input class="sandbox-form__control" type="number" step="1" name="page_max_width_value" value="<?= htmlspecialchars((string) ($styleProfile['page_max_width_value'] ?? 100), ENT_QUOTES, 'UTF-8') ?>">
                        </label>
                        <label class="sandbox-form__label">
                            Unita Larghezza
                            <span class="sandbox-form__help">Usa percentuale per una pagina fluida, pixel per una misura fissa.</span>
                            <select name="page_max_width_unit" class="sandbox-form__control">
                                <?php foreach (['percent', 'px'] as $unit): ?>
                                    <option value="<?= htmlspecialchars($unit, ENT_QUOTES, 'UTF-8') ?>"<?= (($styleProfile['page_max_width_unit'] ?? 'percent') === $unit) ? ' selected' : '' ?>>
                                        <?= htmlspecialchars($unit, ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label class="sandbox-form__label">
                            Padding Pagina
                            <span class="sandbox-form__help">Spazio interno tra bordo pagina e inizio degli slot.</span>
                            <input class="sandbox-form__control" type="number" step="1" name="page_padding_value" value="<?= htmlspecialchars((string) ($styleProfile['page_padding_value'] ?? 18), ENT_QUOTES, 'UTF-8') ?>">
                        </label>
                        <label class="sandbox-form__label">
                            Unita Padding
                            <span class="sandbox-form__help">Di solito `px`; percentuale serve per layout piu elastici.</span>
                            <select name="page_padding_unit" class="sandbox-form__control">
                                <?php foreach (['px', 'percent'] as $unit): ?>
                                    <option value="<?= htmlspecialchars($unit, ENT_QUOTES, 'UTF-8') ?>"<?= (($styleProfile['page_padding_unit'] ?? 'px') === $unit) ? ' selected' : '' ?>>
                                        <?= htmlspecialchars($unit, ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label class="sandbox-form__label">
                            Gap Tra Slot
                            <span class="sandbox-form__help">Distanza verticale tra una sezione e la successiva.</span>
                            <input class="sandbox-form__control" type="number" step="1" name="page_slot_gap_value" value="<?= htmlspecialchars((string) ($styleProfile['page_slot_gap_value'] ?? 12), ENT_QUOTES, 'UTF-8') ?>">
                        </label>
                        <label class="sandbox-form__label">
                            Unita Gap
                            <span class="sandbox-form__help">Controlla l'unita del gap globale tra gli slot.</span>
                            <select name="page_slot_gap_unit" class="sandbox-form__control">
                                <?php foreach (['px', 'percent'] as $unit): ?>
                                    <option value="<?= htmlspecialchars($unit, ENT_QUOTES, 'UTF-8') ?>"<?= (($styleProfile['page_slot_gap_unit'] ?? 'px') === $unit) ? ' selected' : '' ?>>
                                        <?= htmlspecialchars($unit, ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label class="sandbox-form__label">
                            Modalita Background Pagina
                            <span class="sandbox-form__help">Scegli se la pagina usa nessun fondo, colore, gradiente o immagine.</span>
                            <select name="page_background_mode" class="sandbox-form__control">
                                <?php foreach (['none', 'solid', 'gradient', 'image', 'gradient_image'] as $mode): ?>
                                    <option value="<?= htmlspecialchars($mode, ENT_QUOTES, 'UTF-8') ?>"<?= (($styleProfile['page_background_mode'] ?? 'none') === $mode) ? ' selected' : '' ?>>
                                        <?= htmlspecialchars($mode, ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label class="sandbox-form__label">
                            Colore Base Pagina
                            <span class="sandbox-form__help">Colore pieno di base del contenitore pagina.</span>
                            <input class="sandbox-form__control" type="text" name="page_background_color" value="<?= htmlspecialchars((string) ($styleProfile['page_background_color'] ?? '#121212'), ENT_QUOTES, 'UTF-8') ?>" placeholder="#121212 or rgba(...)">
                        </label>
                        <label class="sandbox-form__label">
                            Gradient From
                            <span class="sandbox-form__help">Primo colore del gradiente della pagina.</span>
                            <input class="sandbox-form__control" type="text" name="page_background_gradient_from" value="<?= htmlspecialchars((string) ($styleProfile['page_background_gradient_from'] ?? '#1a1a1a'), ENT_QUOTES, 'UTF-8') ?>" placeholder="#1a1a1a">
                        </label>
                        <label class="sandbox-form__label">
                            Gradient To
                            <span class="sandbox-form__help">Secondo colore del gradiente della pagina.</span>
                            <input class="sandbox-form__control" type="text" name="page_background_gradient_to" value="<?= htmlspecialchars((string) ($styleProfile['page_background_gradient_to'] ?? '#0d0d0d'), ENT_QUOTES, 'UTF-8') ?>" placeholder="#0d0d0d">
                        </label>
                        <label class="sandbox-form__label">
                            Gradient Angle
                            <span class="sandbox-form__help">Angolo del gradiente espresso in gradi.</span>
                            <input class="sandbox-form__control" type="number" step="1" name="page_background_gradient_angle" value="<?= htmlspecialchars((string) ($styleProfile['page_background_gradient_angle'] ?? 180), ENT_QUOTES, 'UTF-8') ?>">
                        </label>
                        <label class="sandbox-form__label">
                            Image URL
                            <span class="sandbox-form__help">URL dell'immagine usata come fondo della pagina.</span>
                            <input class="sandbox-form__control" type="text" name="page_background_image_url" value="<?= htmlspecialchars((string) ($styleProfile['page_background_image_url'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" placeholder="https://...">
                        </label>
                        <label class="sandbox-form__label">
                            Position
                            <span class="sandbox-form__help">Punto di ancoraggio dell'immagine, per esempio `center center`.</span>
                            <input class="sandbox-form__control" type="text" name="page_background_position" value="<?= htmlspecialchars((string) ($styleProfile['page_background_position'] ?? 'center center'), ENT_QUOTES, 'UTF-8') ?>" placeholder="center center">
                        </label>
                        <label class="sandbox-form__label">
                            Size
                            <span class="sandbox-form__help">Come l'immagine riempie la pagina: copre, contiene o usa misura libera.</span>
                            <select name="page_background_size" class="sandbox-form__control">
                                <?php foreach (['cover', 'contain', 'auto', '100% 100%'] as $size): ?>
                                    <option value="<?= htmlspecialchars($size, ENT_QUOTES, 'UTF-8') ?>"<?= (($styleProfile['page_background_size'] ?? 'cover') === $size) ? ' selected' : '' ?>>
                                        <?= htmlspecialchars($size, ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label class="sandbox-form__label">
                            Repeat
                            <span class="sandbox-form__help">Ripetizione dell'immagine di fondo se non copre tutto lo spazio.</span>
                            <select name="page_background_repeat" class="sandbox-form__control">
                                <?php foreach (['no-repeat', 'repeat', 'repeat-x', 'repeat-y'] as $repeat): ?>
                                    <option value="<?= htmlspecialchars($repeat, ENT_QUOTES, 'UTF-8') ?>"<?= (($styleProfile['page_background_repeat'] ?? 'no-repeat') === $repeat) ? ' selected' : '' ?>>
                                        <?= htmlspecialchars($repeat, ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label class="sandbox-form__label">
                            Attachment
                            <span class="sandbox-form__help">Decide se il fondo scorre con la pagina o resta fisso.</span>
                            <select name="page_background_attachment" class="sandbox-form__control">
                                <?php foreach (['scroll', 'fixed', 'local'] as $attachment): ?>
                                    <option value="<?= htmlspecialchars($attachment, ENT_QUOTES, 'UTF-8') ?>"<?= (($styleProfile['page_background_attachment'] ?? 'scroll') === $attachment) ? ' selected' : '' ?>>
                                        <?= htmlspecialchars($attachment, ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label class="sandbox-form__label">
                            Blend Mode
                            <span class="sandbox-form__help">Metodo di fusione tra colore, gradiente e immagine del fondo.</span>
                            <select name="page_background_blend_mode" class="sandbox-form__control">
                                <?php foreach (['normal', 'multiply', 'screen', 'overlay', 'soft-light', 'hard-light'] as $blendMode): ?>
                                    <option value="<?= htmlspecialchars($blendMode, ENT_QUOTES, 'UTF-8') ?>"<?= (($styleProfile['page_background_blend_mode'] ?? 'normal') === $blendMode) ? ' selected' : '' ?>>
                                        <?= htmlspecialchars($blendMode, ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <button class="sandbox-action" type="submit">Salva pagina globale</button>
                    </form>
                </details>
            </aside>

            <section class="sandbox-canvas" data-sandbox-preview>
                <div class="sandbox-stage" data-grid-mode="<?= htmlspecialchars((string) $gridMode, ENT_QUOTES, 'UTF-8') ?>">
                    <p class="sandbox-kicker">Preview</p>
                    <div class="sandbox-page-frame" data-sandbox-page-frame style="<?= htmlspecialchars($pageFrameInlineStyle, ENT_QUOTES, 'UTF-8') ?>">
                    <div
                        class="sandbox-preview-shell<?= $selectedFrame === 'header' ? ' is-selected' : '' ?><?= $headerVisibilityMode === 'hidden' ? ' is-hidden-preview' : '' ?>"
                        data-select-url="<?= htmlspecialchars($buildSandboxUrl(['action' => 'update_state', 'selected_frame' => 'header']), ENT_QUOTES, 'UTF-8') ?>"
                        data-sandbox-preview-header
                        style="<?= htmlspecialchars($headerInlineStyle, ENT_QUOTES, 'UTF-8') ?>"
                    >
                        <div class="sandbox-preview-shell__header sandbox-preview-shell__header--<?= htmlspecialchars((string) $headerLayoutMode, ENT_QUOTES, 'UTF-8') ?>" data-sandbox-preview-header-layout="<?= htmlspecialchars((string) $headerLayoutMode, ENT_QUOTES, 'UTF-8') ?>">
                            <div class="sandbox-preview-shell__brand" data-sandbox-preview-header-brand>
                                <span class="sandbox-preview-shell__brand-visual">
                                    <img
                                        class="sandbox-preview-shell__brand-logo<?= $headerLogoUrl !== '' ? ' is-active' : '' ?>"
                                        data-sandbox-preview-header-logo
                                        src="<?= htmlspecialchars($headerLogoUrl !== '' ? $headerLogoUrl : 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==', ENT_QUOTES, 'UTF-8') ?>"
                                        alt="<?= htmlspecialchars((string) $headerBrandLabel, ENT_QUOTES, 'UTF-8') ?>"
                                        style="<?= htmlspecialchars($headerLogoInlineStyle, ENT_QUOTES, 'UTF-8') ?>"
                                    >
                                    <span class="sandbox-preview-shell__brand-text" data-sandbox-preview-header-brand-text>
                                        <?= htmlspecialchars((string) $headerBrandLabel, ENT_QUOTES, 'UTF-8') ?>
                                    </span>
                                </span>
                            </div>
                            <div class="sandbox-preview-shell__utility">
                                <span class="sandbox-preview-shell__utility-chip">Logo</span>
                                <span class="sandbox-preview-shell__utility-chip">Utility</span>
                            </div>
                        </div>

                        <div class="sandbox-preview-shell__frame-note">
                            Header e footer qui rappresentano la cornice pagina. Masthead resta contenuto alto; Navigation e Footer Navigation sono gli slot dedicati a menu e breadcrumb.
                        </div>
                    </div>

                    <div
                        class="sandbox-system-slot sandbox-system-slot--navigation<?= $navigationSelected ? ' is-selected' : '' ?><?= $selectionMode === 'binding' && !$navigationHasBindingFocus ? ' is-muted' : '' ?><?= !$headerNavigationVisible ? ' is-hidden' : '' ?>"
                        data-slot-key="navigation"
                        data-select-url="<?= htmlspecialchars($buildSandboxUrl(['action' => 'update_state', 'selected_slot' => 'navigation']), ENT_QUOTES, 'UTF-8') ?>"
                    >
                        <div class="sandbox-system-slot__header">
                            <div>
                                <p class="sandbox-slot__eyebrow">System Slot</p>
                                <h3 class="sandbox-system-slot__title">Navigation</h3>
                            </div>
                            <div class="sandbox-slot__meta">
                                <span class="sandbox-field-chip"><?= htmlspecialchars((string) count($navigationBindings), ENT_QUOTES, 'UTF-8') ?> binding</span>
                                <span class="sandbox-field-chip">breadcrumb friendly</span>
                            </div>
                        </div>
                        <div class="sandbox-preview-shell__nav" data-sandbox-preview-header-nav>
                            <?php if ($headerNavigationVisible && is_array($headerMenu) && !empty($headerMenu['items'])): ?>
                                <?php foreach (array_slice($headerMenu['items'], 0, 4) as $item): ?>
                                    <span class="sandbox-preview-shell__nav-item">
                                        <?= htmlspecialchars((string) $item['label'], ENT_QUOTES, 'UTF-8') ?>
                                    </span>
                                <?php endforeach; ?>
                            <?php elseif ($headerNavigationVisible): ?>
                                <span class="sandbox-preview-shell__nav-item">Home</span>
                                <span class="sandbox-preview-shell__nav-item">Chart</span>
                                <span class="sandbox-preview-shell__nav-item">Eventi</span>
                            <?php endif; ?>
                            <span class="sandbox-preview-shell__nav-item sandbox-preview-shell__nav-item--ghost">Breadcrumb</span>
                        </div>
                        <?php if ($navigationPlaceholders !== []): ?>
                            <div class="sandbox-system-slot__note">
                                <?= htmlspecialchars((string) ($navigationPlaceholders[0]['hint'] ?? 'Menu, breadcrumb e utility convivono qui in modo neutro e SEO-friendly.'), ENT_QUOTES, 'UTF-8') ?>
                            </div>
                        <?php else: ?>
                            <div class="sandbox-system-slot__note">Menu, breadcrumb e utility convivono qui in modo neutro e SEO-friendly.</div>
                        <?php endif; ?>
                    </div>

                    <div class="sandbox-stage__intro">
                        <h2 class="sandbox-title"><?= htmlspecialchars((string) ($preview['title'] ?? ''), ENT_QUOTES, 'UTF-8') ?></h2>
                        <p class="sandbox-copy"><?= htmlspecialchars((string) ($preview['subtitle'] ?? ''), ENT_QUOTES, 'UTF-8') ?></p>
                    </div>

                    <div class="sandbox-stage__meta">
                        <span class="sandbox-field-chip">grid: <?= htmlspecialchars((string) $gridMode, ENT_QUOTES, 'UTF-8') ?></span>
                        <?php if ($selectedSource !== null): ?>
                            <span class="sandbox-field-chip">source: <?= htmlspecialchars((string) ($selectedSource['label'] ?? $selectedSourceKey), ENT_QUOTES, 'UTF-8') ?></span>
                        <?php endif; ?>
                        <span class="sandbox-field-chip">slots: <?= htmlspecialchars((string) count($slots), ENT_QUOTES, 'UTF-8') ?></span>
                        <span class="sandbox-field-chip">drag bindings between slots</span>
                    </div>

                    <div class="sandbox-slot-stack<?= in_array($selectionMode, ['binding', 'media'], true) ? ' has-binding-focus' : '' ?>">
                        <?php foreach ($slots as $slot): ?>
                            <?php if (in_array($slot, ['navigation', 'footer_navigation'], true)) { continue; } ?>
                            <?php $slotBindings = $bindingsBySlot[$slot] ?? []; ?>
                            <?php $slotPlaceholders = $placeholdersBySlot[$slot] ?? []; ?>
                            <?php $slotHasSelectedBinding = $selectedBinding !== null && ($selectedBinding['slot_key'] ?? null) === $slot; ?>
                            <?php $slotSettings = $sandbox['slot_settings'][$slot] ?? []; ?>
                            <section
                                class="sandbox-slot<?= $selectionMode === 'slot' && $selectedSlot === $slot ? ' is-selected' : '' ?><?= in_array($selectionMode, ['binding', 'media'], true) && !$slotHasSelectedBinding ? ' is-muted' : '' ?>"
                                data-slot-key="<?= htmlspecialchars((string) $slot, ENT_QUOTES, 'UTF-8') ?>"
                                data-select-url="<?= htmlspecialchars($buildSandboxUrl(['action' => 'update_state', 'selected_slot' => $slot]), ENT_QUOTES, 'UTF-8') ?>"
                                style="<?= htmlspecialchars($resolveSlotInlineStyle($slotSettings), ENT_QUOTES, 'UTF-8') ?>"
                            >
                                <header class="sandbox-slot__header">
                                    <div>
                                        <p class="sandbox-slot__eyebrow">Slot</p>
                                        <h3 class="sandbox-slot__title" data-sandbox-slot-title><?= htmlspecialchars($displaySlotLabel((string) $slot, $slotSettings), ENT_QUOTES, 'UTF-8') ?></h3>
                                    </div>
                                    <div class="sandbox-slot__meta">
                                        <span class="sandbox-field-chip"><?= htmlspecialchars((string) count($slotBindings), ENT_QUOTES, 'UTF-8') ?> binding</span>
                                        <span class="sandbox-field-chip" data-sandbox-slot-layout><?= htmlspecialchars((string) ($slotSettings['layout_mode'] ?? 'stack'), ENT_QUOTES, 'UTF-8') ?></span>
                                        <a class="sandbox-slot__link" href="<?= htmlspecialchars($buildSandboxUrl(['action' => 'update_state', 'selected_slot' => $slot]), ENT_QUOTES, 'UTF-8') ?>">focus</a>
                                    </div>
                                </header>

                                <?php if ($slotPlaceholders !== []): ?>
                                    <div class="sandbox-slot__placeholders">
                                        <?php foreach ($slotPlaceholders as $placeholder): ?>
                                            <div class="sandbox-placeholder<?= $selectedSlot === $slot ? ' is-selected' : '' ?>">
                                                <strong><?= htmlspecialchars((string) ($placeholder['label'] ?? ''), ENT_QUOTES, 'UTF-8') ?></strong>
                                                <p><?= htmlspecialchars((string) ($placeholder['hint'] ?? ''), ENT_QUOTES, 'UTF-8') ?></p>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>

                                <div class="sandbox-slot__canvas" data-drop-slot="<?= htmlspecialchars((string) $slot, ENT_QUOTES, 'UTF-8') ?>">
                                    <?php foreach ($slotBindings as $binding): ?>
                                        <?php
                                        $inlineStyle = $resolveBindingInlineStyle($binding);
                                        $mediaInlineStyle = $resolveBindingMediaStyle($binding);
                                        $isSelectedBinding = (int) $selectedBindingId === (int) $binding['id'];
                                        $hideBinding = in_array($selectionMode, ['binding', 'media'], true) && !$isSelectedBinding;
                                        $visibilityMode = (string) ($binding['visibility_rule']['mode'] ?? 'always');
                                        $layoutConfig = $binding['media_config_json']['layout'] ?? [];
                                        $mediaConfig = $binding['media_config_json']['media'] ?? [];
                                        $showsMedia = $bindingUsesMedia($binding);
                                        ?>
                                        <?php if ($hideBinding) { continue; } ?>
                                        <a
                                            class="sandbox-binding-card<?= $isSelectedBinding ? ' is-selected' : '' ?>"
                                            href="<?= htmlspecialchars($buildSandboxUrl([
                                                'action' => 'select_binding',
                                                'binding_id' => $binding['id'],
                                                'selected_slot' => $binding['slot_key'],
                                                'selected_binding_focus' => 'binding',
                                            ]), ENT_QUOTES, 'UTF-8') ?>"
                                            draggable="true"
                                            data-binding-id="<?= htmlspecialchars((string) $binding['id'], ENT_QUOTES, 'UTF-8') ?>"
                                            data-slot-key="<?= htmlspecialchars((string) $binding['slot_key'], ENT_QUOTES, 'UTF-8') ?>"
                                            data-media-capable="<?= $showsMedia ? '1' : '0' ?>"
                                            data-drop-url="<?= htmlspecialchars($buildSandboxUrl([
                                                'action' => 'reposition_binding',
                                                'binding_id' => $binding['id'],
                                                'selected_slot' => $binding['slot_key'],
                                                'target_slot' => $binding['slot_key'],
                                            ]), ENT_QUOTES, 'UTF-8') ?>"
                                            data-drop-before-url="<?= htmlspecialchars($buildSandboxUrl([
                                                'action' => 'reposition_binding',
                                                'binding_id' => '__BINDING_ID__',
                                                'selected_slot' => $binding['slot_key'],
                                                'target_slot' => $binding['slot_key'],
                                                'before_binding_id' => $binding['id'],
                                            ]), ENT_QUOTES, 'UTF-8') ?>"
                                            style="<?= htmlspecialchars($inlineStyle, ENT_QUOTES, 'UTF-8') ?>"
                                        >
                                            <div
                                                class="sandbox-binding-card__media<?= $showsMedia ? ' is-active' : '' ?>"
                                                data-binding-media
                                                data-select-url="<?= htmlspecialchars($buildSandboxUrl([
                                                    'action' => 'select_binding',
                                                    'binding_id' => $binding['id'],
                                                    'selected_slot' => $binding['slot_key'],
                                                    'selected_binding_focus' => 'media',
                                                ]), ENT_QUOTES, 'UTF-8') ?>"
                                                style="<?= htmlspecialchars($mediaInlineStyle, ENT_QUOTES, 'UTF-8') ?>"
                                            >
                                                <span class="sandbox-binding-card__media-badge">Media</span>
                                                <span class="sandbox-binding-card__media-hint">
                                                    <?= htmlspecialchars((string) (($mediaConfig['fit_mode'] ?? 'cover') . ' / ' . ($mediaConfig['ratio'] ?? 'auto')), ENT_QUOTES, 'UTF-8') ?>
                                                </span>
                                            </div>
                                            <span class="sandbox-binding-card__eyebrow">
                                                <?= htmlspecialchars((string) $binding['field_key'], ENT_QUOTES, 'UTF-8') ?>
                                            </span>
                                            <strong class="sandbox-binding-card__title">
                                                <?= htmlspecialchars((string) ($binding['field_alias'] ?: $binding['field_key']), ENT_QUOTES, 'UTF-8') ?>
                                            </strong>
                                            <p class="sandbox-binding-card__meta">
                                                <?= htmlspecialchars((string) ($dataSourceLabelsById[(int) ($binding['data_source_id'] ?? 0)] ?? ('source #' . (int) ($binding['data_source_id'] ?? 0))), ENT_QUOTES, 'UTF-8') ?>
                                                &middot;
                                                <?= htmlspecialchars((string) $binding['bind_type'], ENT_QUOTES, 'UTF-8') ?>
                                            </p>
                                            <div class="sandbox-binding-card__chips">
                                                <?php if (!empty($binding['width_value'])): ?>
                                                    <span class="sandbox-field-chip">
                                                        width <?= htmlspecialchars((string) $renderMeasure($binding['width_value'], $binding['width_unit'] ?? null), ENT_QUOTES, 'UTF-8') ?>
                                                    </span>
                                                <?php endif; ?>
                                                <?php if (!empty($binding['height_value'])): ?>
                                                    <span class="sandbox-field-chip">
                                                        min-h <?= htmlspecialchars((string) $renderMeasure($binding['height_value'], $binding['height_unit'] ?? null), ENT_QUOTES, 'UTF-8') ?>
                                                    </span>
                                                <?php endif; ?>
                                                <?php if (!empty($layoutConfig['min_width_value'])): ?>
                                                    <span class="sandbox-field-chip">
                                                        min-w <?= htmlspecialchars((string) $renderMeasure($layoutConfig['min_width_value'], $layoutConfig['min_width_unit'] ?? null), ENT_QUOTES, 'UTF-8') ?>
                                                    </span>
                                                <?php endif; ?>
                                                <?php if (!empty($layoutConfig['max_width_value'])): ?>
                                                    <span class="sandbox-field-chip">
                                                        max-w <?= htmlspecialchars((string) $renderMeasure($layoutConfig['max_width_value'], $layoutConfig['max_width_unit'] ?? null), ENT_QUOTES, 'UTF-8') ?>
                                                    </span>
                                                <?php endif; ?>
                                                <?php if (!empty($layoutConfig['padding_value'])): ?>
                                                    <span class="sandbox-field-chip">
                                                        pad <?= htmlspecialchars((string) $renderMeasure($layoutConfig['padding_value'], $layoutConfig['padding_unit'] ?? null), ENT_QUOTES, 'UTF-8') ?>
                                                    </span>
                                                <?php endif; ?>
                                                <?php if (!empty($layoutConfig['border_radius_value'])): ?>
                                                    <span class="sandbox-field-chip">
                                                        radius <?= htmlspecialchars((string) $renderMeasure($layoutConfig['border_radius_value'], $layoutConfig['border_radius_unit'] ?? null), ENT_QUOTES, 'UTF-8') ?>
                                                    </span>
                                                <?php endif; ?>
                                                <?php if (($layoutConfig['background_mode'] ?? 'none') !== 'none'): ?>
                                                    <span class="sandbox-field-chip">
                                                        bg <?= htmlspecialchars((string) ($layoutConfig['background_mode'] ?? 'none'), ENT_QUOTES, 'UTF-8') ?>
                                                    </span>
                                                <?php endif; ?>
                                                <?php if ($showsMedia): ?>
                                                    <span class="sandbox-field-chip">
                                                        media <?= htmlspecialchars((string) ($mediaConfig['fit_mode'] ?? 'cover'), ENT_QUOTES, 'UTF-8') ?>
                                                    </span>
                                                    <span class="sandbox-field-chip">
                                                        ratio <?= htmlspecialchars((string) ($mediaConfig['ratio'] ?? 'auto'), ENT_QUOTES, 'UTF-8') ?>
                                                    </span>
                                                <?php endif; ?>
                                                <span class="sandbox-field-chip"><?= htmlspecialchars($visibilityMode, ENT_QUOTES, 'UTF-8') ?></span>
                                            </div>
                                            <?php if (($binding['fallback_value'] ?? '') !== ''): ?>
                                                <p class="sandbox-binding-card__fallback">
                                                    fallback: <?= htmlspecialchars((string) $binding['fallback_value'], ENT_QUOTES, 'UTF-8') ?>
                                                </p>
                                            <?php endif; ?>
                                        </a>
                                    <?php endforeach; ?>

                                    <?php if ($slotBindings === []): ?>
                                        <div class="sandbox-slot__empty">
                                            Nessun binding su questo slot. Aggiungi un campo dalla colonna destra.
                                        </div>
                                    <?php endif; ?>

                                    <div
                                        class="sandbox-drop-target"
                                        data-drop-url="<?= htmlspecialchars($buildSandboxUrl([
                                            'action' => 'reposition_binding',
                                            'binding_id' => '__BINDING_ID__',
                                            'selected_slot' => $slot,
                                            'target_slot' => $slot,
                                        ]), ENT_QUOTES, 'UTF-8') ?>"
                                    >
                                        Rilascia qui per spostare in fondo allo slot <?= htmlspecialchars((string) $slot, ENT_QUOTES, 'UTF-8') ?>.
                                    </div>
                                </div>
                            </section>
                        <?php endforeach; ?>
                    </div>

                    <div
                        class="sandbox-system-slot sandbox-system-slot--footer-navigation<?= $footerNavigationSelected ? ' is-selected' : '' ?><?= $selectionMode === 'binding' && !$footerNavigationHasBindingFocus ? ' is-muted' : '' ?><?= !$footerNavigationVisible ? ' is-hidden' : '' ?>"
                        data-slot-key="footer_navigation"
                        data-select-url="<?= htmlspecialchars($buildSandboxUrl(['action' => 'update_state', 'selected_slot' => 'footer_navigation']), ENT_QUOTES, 'UTF-8') ?>"
                    >
                        <div class="sandbox-system-slot__header">
                            <div>
                                <p class="sandbox-slot__eyebrow">System Slot</p>
                                <h3 class="sandbox-system-slot__title">Footer Navigation</h3>
                            </div>
                            <div class="sandbox-slot__meta">
                                <span class="sandbox-field-chip"><?= htmlspecialchars((string) count($footerNavigationBindings), ENT_QUOTES, 'UTF-8') ?> binding</span>
                                <span class="sandbox-field-chip">service links</span>
                            </div>
                        </div>
                        <div class="sandbox-preview-shell__footer-links<?= !$footerNavigationVisible ? ' is-hidden' : '' ?>">
                            <span class="sandbox-preview-shell__nav-item">Home</span>
                            <span class="sandbox-preview-shell__nav-item">Privacy</span>
                            <span class="sandbox-preview-shell__nav-item">Contatti</span>
                            <span class="sandbox-preview-shell__nav-item sandbox-preview-shell__nav-item--ghost">Breadcrumb</span>
                        </div>
                        <?php if ($footerNavigationPlaceholders !== []): ?>
                            <div class="sandbox-system-slot__note">
                                <?= htmlspecialchars((string) ($footerNavigationPlaceholders[0]['hint'] ?? 'Link di servizio, breadcrumb secondario e nav inferiore convivono qui.'), ENT_QUOTES, 'UTF-8') ?>
                            </div>
                        <?php else: ?>
                            <div class="sandbox-system-slot__note">Link di servizio, breadcrumb secondario e nav inferiore convivono qui.</div>
                        <?php endif; ?>
                    </div>

                    <div
                        class="sandbox-preview-shell sandbox-preview-shell--footer<?= $selectedFrame === 'footer' ? ' is-selected' : '' ?><?= (($styleProfile['footer_visibility_mode'] ?? 'visible') === 'hidden') ? ' is-hidden-preview' : '' ?>"
                        data-select-url="<?= htmlspecialchars($buildSandboxUrl(['action' => 'update_state', 'selected_frame' => 'footer']), ENT_QUOTES, 'UTF-8') ?>"
                        data-sandbox-preview-footer
                        style="<?= htmlspecialchars($footerInlineStyle, ENT_QUOTES, 'UTF-8') ?>"
                    >
                        <div class="sandbox-preview-shell__footer sandbox-preview-shell__footer--<?= htmlspecialchars((string) $footerLayoutMode, ENT_QUOTES, 'UTF-8') ?>" data-sandbox-preview-footer-layout="<?= htmlspecialchars((string) $footerLayoutMode, ENT_QUOTES, 'UTF-8') ?>">
                            <div>
                                <strong class="sandbox-preview-shell__footer-title" data-sandbox-preview-footer-title><?= htmlspecialchars((string) $footerLabel, ENT_QUOTES, 'UTF-8') ?></strong>
                                <p class="sandbox-preview-shell__footer-copy">
                                    Chiusura pagina, link di servizio e richiamo brand.
                                </p>
                            </div>
                            <div class="sandbox-preview-shell__footer-links<?= !$footerNavigationVisible ? ' is-hidden' : '' ?>" data-sandbox-preview-footer-nav>
                                <span class="sandbox-preview-shell__nav-item">Contatti</span>
                                <span class="sandbox-preview-shell__nav-item">Privacy</span>
                                <span class="sandbox-preview-shell__nav-item">Live Hub</span>
                            </div>
                        </div>
                        <div class="sandbox-preview-shell__frame-note">
                            Footer e chiusura pagina restano una cornice separata, mentre la navbar inferiore vive nel suo slot dedicato.
                        </div>
                    </div>
                    </div>
                </div>
            </section>

            <aside class="sandbox-preview" data-sandbox-sidebar>
                <p class="sandbox-kicker">Focus</p>
                <div class="sandbox-focus-card">
                    <p class="sandbox-focus-card__eyebrow">
                        <?= htmlspecialchars($selectionMode === 'media' ? 'Immagine' : ($selectionMode === 'binding' ? 'Componente' : ($selectionMode === 'frame' ? 'Cornice' : 'Slot')), ENT_QUOTES, 'UTF-8') ?>
                    </p>
                    <h3 class="sandbox-focus-card__title"><?= htmlspecialchars($selectionTitle, ENT_QUOTES, 'UTF-8') ?></h3>
                    <p class="sandbox-focus-card__copy"><?= htmlspecialchars($selectionSubtitle, ENT_QUOTES, 'UTF-8') ?></p>
                    <p class="sandbox-focus-card__hint"><?= htmlspecialchars($selectionHelp, ENT_QUOTES, 'UTF-8') ?></p>
                </div>
                <?php if (in_array($selectionMode, ['frame'], true) || $selectionMode === 'slot' && $selectedSlot === null): ?>
                    <p class="sandbox-meta">Sorgente stile live: <?= htmlspecialchars((string) $styleSourceModelKey, ENT_QUOTES, 'UTF-8') ?> (<?= htmlspecialchars((string) $styleSourceModelType, ENT_QUOTES, 'UTF-8') ?>)</p>
                <?php endif; ?>

                <?php if ($selectionMode === 'frame' && $selectedFrame === 'header'): ?>
                    <p class="sandbox-kicker">Opzioni Header</p>
                    <form class="sandbox-form" method="post" action="<?= htmlspecialchars($basePath . '/sandbox', ENT_QUOTES, 'UTF-8') ?>" enctype="multipart/form-data" data-sandbox-sidebar-focus data-sandbox-preview-form="header">
                        <input type="hidden" name="model" value="<?= htmlspecialchars((string) $modelKey, ENT_QUOTES, 'UTF-8') ?>">
                        <input type="hidden" name="version" value="<?= htmlspecialchars((string) $currentVersionNo, ENT_QUOTES, 'UTF-8') ?>">
                        <input type="hidden" name="selected_frame" value="header">
                        <input type="hidden" name="action" value="update_header_options" data-sandbox-header-action>
                        <label class="sandbox-form__label">
                            Brand Label
                            <span class="sandbox-form__help">Nome mostrato nel brand della cornice header.</span>
                            <input class="sandbox-form__control" type="text" name="header_brand_label" value="<?= htmlspecialchars((string) ($styleProfile['header_brand_label'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" placeholder="KR World">
                        </label>
                        <div class="sandbox-media-source" data-sandbox-media-source>
                            <p class="sandbox-media-source__title">Sorgente Media Logo</p>
                            <p class="sandbox-media-source__help">Scegli se prendere il logo dalla libreria, caricarlo dal desktop o usare un URL web.</p>
                            <label class="sandbox-form__label">
                                Fonte
                                <span class="sandbox-form__help">`library` usa la cartella del progetto, `upload` carica dal tuo PC, `web` usa un URL esterno.</span>
                                <select class="sandbox-form__control" data-sandbox-media-mode>
                                    <option value="library"<?= $headerLogoSourceMode === 'library' ? ' selected' : '' ?>>Libreria</option>
                                    <option value="upload">Upload Desktop</option>
                                    <option value="web"<?= $headerLogoSourceMode === 'web' ? ' selected' : '' ?>>Web URL</option>
                                </select>
                            </label>
                            <div class="sandbox-media-source__preview<?= $headerLogoUrl !== '' ? ' is-active' : '' ?>" data-sandbox-logo-preview>
                                <?php if ($headerLogoUrl !== ''): ?>
                                    <img src="<?= htmlspecialchars($headerLogoUrl, ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars((string) $headerBrandLabel, ENT_QUOTES, 'UTF-8') ?>">
                                <?php else: ?>
                                    <span>Nessun logo selezionato</span>
                                <?php endif; ?>
                            </div>
                            <div class="sandbox-media-source__section<?= $headerLogoSourceMode === 'library' ? ' is-active' : '' ?>" data-sandbox-media-section="library">
                                <label class="sandbox-form__label">
                                    Logo Da Libreria
                                    <span class="sandbox-form__help">Seleziona un file gia` caricato nella cartella logo del progetto.</span>
                                    <select class="sandbox-form__control" data-sandbox-logo-picker>
                                        <option value="">Seleziona un logo...</option>
                                        <?php foreach ($logoLibrary as $logoItem): ?>
                                            <option value="<?= htmlspecialchars((string) $logoItem['url'], ENT_QUOTES, 'UTF-8') ?>"<?= (($styleProfile['header_logo_url'] ?? '') === ($logoItem['url'] ?? '')) ? ' selected' : '' ?>>
                                                <?= htmlspecialchars((string) $logoItem['name'], ENT_QUOTES, 'UTF-8') ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </label>
                            </div>
                            <div class="sandbox-media-source__section<?= $headerLogoSourceMode === 'web' ? ' is-active' : '' ?>" data-sandbox-media-section="web">
                                <label class="sandbox-form__label">
                                    Logo URL
                                    <span class="sandbox-form__help">URL web esterno o percorso pubblico locale del file.</span>
                                    <input class="sandbox-form__control" type="text" name="header_logo_url" value="<?= htmlspecialchars((string) ($styleProfile['header_logo_url'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" placeholder="https://... oppure /assets/media/logos/... " data-sandbox-logo-url-input>
                                </label>
                            </div>
                            <div class="sandbox-media-source__section<?= $headerLogoSourceMode === 'upload' ? ' is-active' : '' ?>" data-sandbox-media-section="upload">
                                <label class="sandbox-form__label">
                                    Upload Da Desktop
                                    <span class="sandbox-form__help">Carica un file dal tuo computer nella cartella `public/assets/media/logos`.</span>
                                    <input class="sandbox-form__control" type="file" name="header_logo_upload" accept=".png,.jpg,.jpeg,.webp,.gif,.svg,image/png,image/jpeg,image/webp,image/gif,image/svg+xml" data-sandbox-upload-input>
                                </label>
                            </div>
                        </div>
                        <label class="sandbox-form__label">
                            Logo Width
                            <span class="sandbox-form__help">Larghezza reale del file logo, modificabile se vuoi scalarlo.</span>
                            <input class="sandbox-form__control" type="number" step="1" name="header_logo_width_value" value="<?= htmlspecialchars((string) ($headerLogoWidthValue ?? ''), ENT_QUOTES, 'UTF-8') ?>" data-sandbox-proportional-group="header-logo" data-sandbox-proportional-role="width">
                        </label>
                        <label class="sandbox-form__label">
                            Unita Width
                            <span class="sandbox-form__help">Unita della larghezza logo.</span>
                            <select name="header_logo_width_unit" class="sandbox-form__control">
                                <?php foreach (['px', 'percent'] as $unit): ?>
                                    <option value="<?= htmlspecialchars($unit, ENT_QUOTES, 'UTF-8') ?>"<?= (($styleProfile['header_logo_width_unit'] ?? 'px') === $unit) ? ' selected' : '' ?>>
                                        <?= htmlspecialchars($unit, ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label class="sandbox-form__label">
                            Logo Height
                            <span class="sandbox-form__help">Altezza reale del file logo, modificabile se vuoi scalarlo.</span>
                            <input class="sandbox-form__control" type="number" step="1" name="header_logo_height_value" value="<?= htmlspecialchars((string) ($headerLogoHeightValue ?? ''), ENT_QUOTES, 'UTF-8') ?>" data-sandbox-proportional-group="header-logo" data-sandbox-proportional-role="height">
                        </label>
                        <label class="sandbox-form__label">
                            Unita Height
                            <span class="sandbox-form__help">Unita dell'altezza logo.</span>
                            <select name="header_logo_height_unit" class="sandbox-form__control">
                                <?php foreach (['px', 'percent'] as $unit): ?>
                                    <option value="<?= htmlspecialchars($unit, ENT_QUOTES, 'UTF-8') ?>"<?= (($styleProfile['header_logo_height_unit'] ?? 'px') === $unit) ? ' selected' : '' ?>>
                                        <?= htmlspecialchars($unit, ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label class="sandbox-form__label">
                            Logo Max Height
                            <span class="sandbox-form__help">Limite massimo utile per mantenere il logo leggibile senza sforare il frame.</span>
                            <input class="sandbox-form__control" type="number" step="1" name="header_logo_max_height_value" value="<?= htmlspecialchars((string) ($headerLogoMaxHeightValue ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                        </label>
                        <label class="sandbox-form__label">
                            Unita Max Height
                            <span class="sandbox-form__help">Unita del limite massimo altezza.</span>
                            <select name="header_logo_max_height_unit" class="sandbox-form__control">
                                <?php foreach (['px', 'percent'] as $unit): ?>
                                    <option value="<?= htmlspecialchars($unit, ENT_QUOTES, 'UTF-8') ?>"<?= (($styleProfile['header_logo_max_height_unit'] ?? 'px') === $unit) ? ' selected' : '' ?>>
                                        <?= htmlspecialchars($unit, ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label class="sandbox-form__label">
                            Logo Scale
                            <span class="sandbox-form__help">Modo di adattamento del logo dentro il suo box: di solito `contain`.</span>
                            <select name="header_logo_scale_mode" class="sandbox-form__control">
                                <?php foreach (['contain', 'cover', 'fill'] as $mode): ?>
                                    <option value="<?= htmlspecialchars($mode, ENT_QUOTES, 'UTF-8') ?>"<?= (($styleProfile['header_logo_scale_mode'] ?? 'contain') === $mode) ? ' selected' : '' ?>>
                                        <?= htmlspecialchars($mode, ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label class="sandbox-form__label">
                            Visibilita Header
                            <span class="sandbox-form__help">Mostra o nasconde la cornice header della pagina.</span>
                            <select name="header_visibility_mode" class="sandbox-form__control">
                                <?php foreach (['visible', 'hidden'] as $mode): ?>
                                    <option value="<?= htmlspecialchars($mode, ENT_QUOTES, 'UTF-8') ?>"<?= (($styleProfile['header_visibility_mode'] ?? 'visible') === $mode) ? ' selected' : '' ?>>
                                        <?= htmlspecialchars($mode, ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label class="sandbox-form__label">
                            Layout Header
                            <span class="sandbox-form__help">Disposizione di brand, logo e utility dentro la cornice.</span>
                            <select name="header_layout_mode" class="sandbox-form__control">
                                <?php foreach (['split', 'stack', 'center'] as $mode): ?>
                                    <option value="<?= htmlspecialchars($mode, ENT_QUOTES, 'UTF-8') ?>"<?= (($styleProfile['header_layout_mode'] ?? 'split') === $mode) ? ' selected' : '' ?>>
                                        <?= htmlspecialchars($mode, ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label class="sandbox-form__label">
                            Padding Header
                            <span class="sandbox-form__help">Spazio interno della cornice header.</span>
                            <input class="sandbox-form__control" type="number" step="1" name="header_padding_value" value="<?= htmlspecialchars((string) ($styleProfile['header_padding_value'] ?? 12), ENT_QUOTES, 'UTF-8') ?>">
                        </label>
                        <label class="sandbox-form__label">
                            Unita Padding
                            <span class="sandbox-form__help">Unita del padding dell'header.</span>
                            <select name="header_padding_unit" class="sandbox-form__control">
                                <?php foreach (['px', 'percent'] as $unit): ?>
                                    <option value="<?= htmlspecialchars($unit, ENT_QUOTES, 'UTF-8') ?>"<?= (($styleProfile['header_padding_unit'] ?? 'px') === $unit) ? ' selected' : '' ?>>
                                        <?= htmlspecialchars($unit, ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label class="sandbox-form__label">
                            Altezza Header
                            <span class="sandbox-form__help">Altezza minima della cornice header nella preview.</span>
                            <input class="sandbox-form__control" type="number" step="1" name="header_height_value" value="<?= htmlspecialchars((string) ($styleProfile['header_height_value'] ?? 64), ENT_QUOTES, 'UTF-8') ?>">
                        </label>
                        <label class="sandbox-form__label">
                            Unita Altezza
                            <span class="sandbox-form__help">Unita dell'altezza header.</span>
                            <select name="header_height_unit" class="sandbox-form__control">
                                <?php foreach (['px', 'percent'] as $unit): ?>
                                    <option value="<?= htmlspecialchars($unit, ENT_QUOTES, 'UTF-8') ?>"<?= (($styleProfile['header_height_unit'] ?? 'px') === $unit) ? ' selected' : '' ?>>
                                        <?= htmlspecialchars($unit, ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label class="sandbox-form__label">
                            Gap Header
                            <span class="sandbox-form__help">Distanza interna tra gli elementi del frame header.</span>
                            <input class="sandbox-form__control" type="number" step="1" name="header_gap_value" value="<?= htmlspecialchars((string) ($styleProfile['header_gap_value'] ?? 16), ENT_QUOTES, 'UTF-8') ?>">
                        </label>
                        <label class="sandbox-form__label">
                            Unita Gap
                            <span class="sandbox-form__help">Unita del gap interno dell'header.</span>
                            <select name="header_gap_unit" class="sandbox-form__control">
                                <?php foreach (['px', 'percent'] as $unit): ?>
                                    <option value="<?= htmlspecialchars($unit, ENT_QUOTES, 'UTF-8') ?>"<?= (($styleProfile['header_gap_unit'] ?? 'px') === $unit) ? ' selected' : '' ?>>
                                        <?= htmlspecialchars($unit, ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label class="sandbox-form__label">
                            Background Mode
                            <span class="sandbox-form__help">Tipo di fondo della cornice header.</span>
                            <select name="header_background_mode" class="sandbox-form__control">
                                <?php foreach (['none', 'solid', 'gradient', 'image', 'gradient_image'] as $mode): ?>
                                    <option value="<?= htmlspecialchars($mode, ENT_QUOTES, 'UTF-8') ?>"<?= (($styleProfile['header_background_mode'] ?? 'none') === $mode) ? ' selected' : '' ?>>
                                        <?= htmlspecialchars($mode, ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label class="sandbox-form__label">
                            Background Color
                            <span class="sandbox-form__help">Colore base del fondo header.</span>
                            <input class="sandbox-form__control" type="text" name="header_background_color" value="<?= htmlspecialchars((string) ($styleProfile['header_background_color'] ?? '#090909'), ENT_QUOTES, 'UTF-8') ?>" placeholder="#090909 or rgba(...)">
                        </label>
                        <label class="sandbox-form__label">
                            Gradient From
                            <span class="sandbox-form__help">Primo colore del gradiente header.</span>
                            <input class="sandbox-form__control" type="text" name="header_background_gradient_from" value="<?= htmlspecialchars((string) ($styleProfile['header_background_gradient_from'] ?? '#121212'), ENT_QUOTES, 'UTF-8') ?>" placeholder="#121212">
                        </label>
                        <label class="sandbox-form__label">
                            Gradient To
                            <span class="sandbox-form__help">Secondo colore del gradiente header.</span>
                            <input class="sandbox-form__control" type="text" name="header_background_gradient_to" value="<?= htmlspecialchars((string) ($styleProfile['header_background_gradient_to'] ?? '#090909'), ENT_QUOTES, 'UTF-8') ?>" placeholder="#090909">
                        </label>
                        <label class="sandbox-form__label">
                            Gradient Angle
                            <span class="sandbox-form__help">Angolo del gradiente header espresso in gradi.</span>
                            <input class="sandbox-form__control" type="number" step="1" name="header_background_gradient_angle" value="<?= htmlspecialchars((string) ($styleProfile['header_background_gradient_angle'] ?? 180), ENT_QUOTES, 'UTF-8') ?>">
                        </label>
                        <label class="sandbox-form__label">
                            Image URL
                            <span class="sandbox-form__help">Immagine di fondo della cornice header.</span>
                            <input class="sandbox-form__control" type="text" name="header_background_image_url" value="<?= htmlspecialchars((string) ($styleProfile['header_background_image_url'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" placeholder="https://...">
                        </label>
                        <label class="sandbox-form__label">
                            Position
                            <span class="sandbox-form__help">Ancoraggio del fondo header, per esempio `center center`.</span>
                            <input class="sandbox-form__control" type="text" name="header_background_position" value="<?= htmlspecialchars((string) ($styleProfile['header_background_position'] ?? 'center center'), ENT_QUOTES, 'UTF-8') ?>" placeholder="center center">
                        </label>
                        <label class="sandbox-form__label">
                            Size
                            <span class="sandbox-form__help">Come l'immagine riempie la cornice header.</span>
                            <select name="header_background_size" class="sandbox-form__control">
                                <?php foreach (['cover', 'contain', 'auto', '100% 100%'] as $size): ?>
                                    <option value="<?= htmlspecialchars($size, ENT_QUOTES, 'UTF-8') ?>"<?= (($styleProfile['header_background_size'] ?? 'cover') === $size) ? ' selected' : '' ?>>
                                        <?= htmlspecialchars($size, ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label class="sandbox-form__label">
                            Repeat
                            <span class="sandbox-form__help">Ripetizione del fondo header.</span>
                            <select name="header_background_repeat" class="sandbox-form__control">
                                <?php foreach (['no-repeat', 'repeat', 'repeat-x', 'repeat-y'] as $repeat): ?>
                                    <option value="<?= htmlspecialchars($repeat, ENT_QUOTES, 'UTF-8') ?>"<?= (($styleProfile['header_background_repeat'] ?? 'no-repeat') === $repeat) ? ' selected' : '' ?>>
                                        <?= htmlspecialchars($repeat, ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label class="sandbox-form__label">
                            Attachment
                            <span class="sandbox-form__help">Decide se il fondo header scorre o resta fisso.</span>
                            <select name="header_background_attachment" class="sandbox-form__control">
                                <?php foreach (['scroll', 'fixed', 'local'] as $attachment): ?>
                                    <option value="<?= htmlspecialchars($attachment, ENT_QUOTES, 'UTF-8') ?>"<?= (($styleProfile['header_background_attachment'] ?? 'scroll') === $attachment) ? ' selected' : '' ?>>
                                        <?= htmlspecialchars($attachment, ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label class="sandbox-form__label">
                            Blend Mode
                            <span class="sandbox-form__help">Fusione tra colore, gradiente e immagine del fondo header.</span>
                            <select name="header_background_blend_mode" class="sandbox-form__control">
                                <?php foreach (['normal', 'multiply', 'screen', 'overlay', 'soft-light', 'hard-light'] as $blendMode): ?>
                                    <option value="<?= htmlspecialchars($blendMode, ENT_QUOTES, 'UTF-8') ?>"<?= (($styleProfile['header_background_blend_mode'] ?? 'normal') === $blendMode) ? ' selected' : '' ?>>
                                        <?= htmlspecialchars($blendMode, ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <div class="sandbox-form__actions">
                            <button class="sandbox-action" type="submit" data-sandbox-submit-action="update_header_options">Salva opzioni header</button>
                            <button class="sandbox-action sandbox-action--ghost" type="submit" data-sandbox-submit-action="upload_header_logo">Upload logo</button>
                        </div>
                    </form>
                <?php endif; ?>

                <?php if ($selectionMode === 'frame' && $selectedFrame === 'footer'): ?>
                    <p class="sandbox-kicker">Opzioni Footer</p>
                    <form class="sandbox-form" method="get" action="<?= htmlspecialchars($basePath . '/sandbox', ENT_QUOTES, 'UTF-8') ?>" data-sandbox-sidebar-focus data-sandbox-preview-form="footer">
                        <input type="hidden" name="model" value="<?= htmlspecialchars((string) $modelKey, ENT_QUOTES, 'UTF-8') ?>">
                        <input type="hidden" name="version" value="<?= htmlspecialchars((string) $currentVersionNo, ENT_QUOTES, 'UTF-8') ?>">
                        <input type="hidden" name="action" value="update_footer_options">
                        <input type="hidden" name="selected_frame" value="footer">
                        <label class="sandbox-form__label">
                            Footer Label
                            <span class="sandbox-form__help">Titolo mostrato nella cornice footer.</span>
                            <input class="sandbox-form__control" type="text" name="footer_label" value="<?= htmlspecialchars((string) ($styleProfile['footer_label'] ?? 'Footer'), ENT_QUOTES, 'UTF-8') ?>" placeholder="Footer">
                        </label>
                        <label class="sandbox-form__label">
                            Visibilita Footer
                            <span class="sandbox-form__help">Mostra o nasconde la cornice footer della pagina.</span>
                            <select name="footer_visibility_mode" class="sandbox-form__control">
                                <?php foreach (['visible', 'hidden'] as $mode): ?>
                                    <option value="<?= htmlspecialchars($mode, ENT_QUOTES, 'UTF-8') ?>"<?= (($styleProfile['footer_visibility_mode'] ?? 'visible') === $mode) ? ' selected' : '' ?>>
                                        <?= htmlspecialchars($mode, ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label class="sandbox-form__label">
                            Layout Footer
                            <span class="sandbox-form__help">Disposizione del contenuto nel footer.</span>
                            <select name="footer_layout_mode" class="sandbox-form__control">
                                <?php foreach (['split', 'stack', 'center'] as $mode): ?>
                                    <option value="<?= htmlspecialchars($mode, ENT_QUOTES, 'UTF-8') ?>"<?= (($styleProfile['footer_layout_mode'] ?? 'split') === $mode) ? ' selected' : '' ?>>
                                        <?= htmlspecialchars($mode, ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label class="sandbox-form__label">
                            Padding Footer
                            <span class="sandbox-form__help">Spazio interno della cornice footer.</span>
                            <input class="sandbox-form__control" type="number" step="1" name="footer_padding_value" value="<?= htmlspecialchars((string) ($styleProfile['footer_padding_value'] ?? 12), ENT_QUOTES, 'UTF-8') ?>">
                        </label>
                        <label class="sandbox-form__label">
                            Unita Padding
                            <span class="sandbox-form__help">Unita del padding del footer.</span>
                            <select name="footer_padding_unit" class="sandbox-form__control">
                                <?php foreach (['px', 'percent'] as $unit): ?>
                                    <option value="<?= htmlspecialchars($unit, ENT_QUOTES, 'UTF-8') ?>"<?= (($styleProfile['footer_padding_unit'] ?? 'px') === $unit) ? ' selected' : '' ?>>
                                        <?= htmlspecialchars($unit, ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label class="sandbox-form__label">
                            Altezza Footer
                            <span class="sandbox-form__help">Altezza minima della cornice footer nella preview.</span>
                            <input class="sandbox-form__control" type="number" step="1" name="footer_height_value" value="<?= htmlspecialchars((string) ($styleProfile['footer_height_value'] ?? 72), ENT_QUOTES, 'UTF-8') ?>">
                        </label>
                        <label class="sandbox-form__label">
                            Unita Altezza
                            <span class="sandbox-form__help">Unita dell'altezza footer.</span>
                            <select name="footer_height_unit" class="sandbox-form__control">
                                <?php foreach (['px', 'percent'] as $unit): ?>
                                    <option value="<?= htmlspecialchars($unit, ENT_QUOTES, 'UTF-8') ?>"<?= (($styleProfile['footer_height_unit'] ?? 'px') === $unit) ? ' selected' : '' ?>>
                                        <?= htmlspecialchars($unit, ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label class="sandbox-form__label">
                            Gap Footer
                            <span class="sandbox-form__help">Distanza tra blocco testo e link del footer.</span>
                            <input class="sandbox-form__control" type="number" step="1" name="footer_gap_value" value="<?= htmlspecialchars((string) ($styleProfile['footer_gap_value'] ?? 16), ENT_QUOTES, 'UTF-8') ?>">
                        </label>
                        <label class="sandbox-form__label">
                            Unita Gap
                            <span class="sandbox-form__help">Unita del gap interno del footer.</span>
                            <select name="footer_gap_unit" class="sandbox-form__control">
                                <?php foreach (['px', 'percent'] as $unit): ?>
                                    <option value="<?= htmlspecialchars($unit, ENT_QUOTES, 'UTF-8') ?>"<?= (($styleProfile['footer_gap_unit'] ?? 'px') === $unit) ? ' selected' : '' ?>>
                                        <?= htmlspecialchars($unit, ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label class="sandbox-form__label">
                            Background Mode
                            <span class="sandbox-form__help">Tipo di fondo della cornice footer.</span>
                            <select name="footer_background_mode" class="sandbox-form__control">
                                <?php foreach (['none', 'solid', 'gradient', 'image', 'gradient_image'] as $mode): ?>
                                    <option value="<?= htmlspecialchars($mode, ENT_QUOTES, 'UTF-8') ?>"<?= (($styleProfile['footer_background_mode'] ?? 'none') === $mode) ? ' selected' : '' ?>>
                                        <?= htmlspecialchars($mode, ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label class="sandbox-form__label">
                            Background Color
                            <span class="sandbox-form__help">Colore base del fondo footer.</span>
                            <input class="sandbox-form__control" type="text" name="footer_background_color" value="<?= htmlspecialchars((string) ($styleProfile['footer_background_color'] ?? '#090909'), ENT_QUOTES, 'UTF-8') ?>" placeholder="#090909 or rgba(...)">
                        </label>
                        <label class="sandbox-form__label">
                            Gradient From
                            <span class="sandbox-form__help">Primo colore del gradiente footer.</span>
                            <input class="sandbox-form__control" type="text" name="footer_background_gradient_from" value="<?= htmlspecialchars((string) ($styleProfile['footer_background_gradient_from'] ?? '#121212'), ENT_QUOTES, 'UTF-8') ?>" placeholder="#121212">
                        </label>
                        <label class="sandbox-form__label">
                            Gradient To
                            <span class="sandbox-form__help">Secondo colore del gradiente footer.</span>
                            <input class="sandbox-form__control" type="text" name="footer_background_gradient_to" value="<?= htmlspecialchars((string) ($styleProfile['footer_background_gradient_to'] ?? '#090909'), ENT_QUOTES, 'UTF-8') ?>" placeholder="#090909">
                        </label>
                        <label class="sandbox-form__label">
                            Gradient Angle
                            <span class="sandbox-form__help">Angolo del gradiente footer espresso in gradi.</span>
                            <input class="sandbox-form__control" type="number" step="1" name="footer_background_gradient_angle" value="<?= htmlspecialchars((string) ($styleProfile['footer_background_gradient_angle'] ?? 180), ENT_QUOTES, 'UTF-8') ?>">
                        </label>
                        <label class="sandbox-form__label">
                            Image URL
                            <span class="sandbox-form__help">Immagine di fondo della cornice footer.</span>
                            <input class="sandbox-form__control" type="text" name="footer_background_image_url" value="<?= htmlspecialchars((string) ($styleProfile['footer_background_image_url'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" placeholder="https://...">
                        </label>
                        <label class="sandbox-form__label">
                            Position
                            <span class="sandbox-form__help">Ancoraggio del fondo footer, per esempio `center center`.</span>
                            <input class="sandbox-form__control" type="text" name="footer_background_position" value="<?= htmlspecialchars((string) ($styleProfile['footer_background_position'] ?? 'center center'), ENT_QUOTES, 'UTF-8') ?>" placeholder="center center">
                        </label>
                        <label class="sandbox-form__label">
                            Size
                            <span class="sandbox-form__help">Come l'immagine riempie la cornice footer.</span>
                            <select name="footer_background_size" class="sandbox-form__control">
                                <?php foreach (['cover', 'contain', 'auto', '100% 100%'] as $size): ?>
                                    <option value="<?= htmlspecialchars($size, ENT_QUOTES, 'UTF-8') ?>"<?= (($styleProfile['footer_background_size'] ?? 'cover') === $size) ? ' selected' : '' ?>>
                                        <?= htmlspecialchars($size, ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label class="sandbox-form__label">
                            Repeat
                            <span class="sandbox-form__help">Ripetizione del fondo footer.</span>
                            <select name="footer_background_repeat" class="sandbox-form__control">
                                <?php foreach (['no-repeat', 'repeat', 'repeat-x', 'repeat-y'] as $repeat): ?>
                                    <option value="<?= htmlspecialchars($repeat, ENT_QUOTES, 'UTF-8') ?>"<?= (($styleProfile['footer_background_repeat'] ?? 'no-repeat') === $repeat) ? ' selected' : '' ?>>
                                        <?= htmlspecialchars($repeat, ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label class="sandbox-form__label">
                            Attachment
                            <span class="sandbox-form__help">Decide se il fondo footer scorre o resta fisso.</span>
                            <select name="footer_background_attachment" class="sandbox-form__control">
                                <?php foreach (['scroll', 'fixed', 'local'] as $attachment): ?>
                                    <option value="<?= htmlspecialchars($attachment, ENT_QUOTES, 'UTF-8') ?>"<?= (($styleProfile['footer_background_attachment'] ?? 'scroll') === $attachment) ? ' selected' : '' ?>>
                                        <?= htmlspecialchars($attachment, ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label class="sandbox-form__label">
                            Blend Mode
                            <span class="sandbox-form__help">Fusione tra colore, gradiente e immagine del fondo footer.</span>
                            <select name="footer_background_blend_mode" class="sandbox-form__control">
                                <?php foreach (['normal', 'multiply', 'screen', 'overlay', 'soft-light', 'hard-light'] as $blendMode): ?>
                                    <option value="<?= htmlspecialchars($blendMode, ENT_QUOTES, 'UTF-8') ?>"<?= (($styleProfile['footer_background_blend_mode'] ?? 'normal') === $blendMode) ? ' selected' : '' ?>>
                                        <?= htmlspecialchars($blendMode, ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <button class="sandbox-action" type="submit">Salva opzioni footer</button>
                    </form>
                <?php endif; ?>

                <?php if ($selectionMode === 'slot'): ?>
                    <p class="sandbox-kicker">Opzioni Sezione</p>
                    <form class="sandbox-form" method="get" action="<?= htmlspecialchars($basePath . '/sandbox', ENT_QUOTES, 'UTF-8') ?>" data-sandbox-sidebar-focus data-sandbox-preview-form="slot">
                        <input type="hidden" name="model" value="<?= htmlspecialchars((string) $modelKey, ENT_QUOTES, 'UTF-8') ?>">
                        <input type="hidden" name="version" value="<?= htmlspecialchars((string) $currentVersionNo, ENT_QUOTES, 'UTF-8') ?>">
                        <input type="hidden" name="action" value="update_slot_options">
                        <input type="hidden" name="selected_slot" value="<?= htmlspecialchars((string) $selectedSlot, ENT_QUOTES, 'UTF-8') ?>">
                        <label class="sandbox-form__label">
                            Label Sezione
                            <span class="sandbox-form__help">Nome editoriale dello slot mostrato nella preview e nel pannello.</span>
                            <input class="sandbox-form__control" type="text" name="slot_label" value="<?= htmlspecialchars($selectedSlotLabel, ENT_QUOTES, 'UTF-8') ?>">
                        </label>
                        <label class="sandbox-form__label">
                            Layout
                            <span class="sandbox-form__help">Organizzazione prevista per i componenti dello slot: verticale, griglia o split.</span>
                            <select name="slot_layout_mode" class="sandbox-form__control">
                                <?php foreach (['stack', 'grid', 'split'] as $layoutMode): ?>
                                    <option value="<?= htmlspecialchars($layoutMode, ENT_QUOTES, 'UTF-8') ?>"<?= (($selectedSlotSettings['layout_mode'] ?? 'stack') === $layoutMode) ? ' selected' : '' ?>>
                                        <?= htmlspecialchars($layoutMode, ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label class="sandbox-form__label">
                            Padding
                            <span class="sandbox-form__help">Spazio interno della sezione, tra bordo slot e componenti.</span>
                            <input class="sandbox-form__control" type="number" step="1" name="slot_padding_value" value="<?= htmlspecialchars((string) ($selectedSlotSettings['padding_value'] ?? 24), ENT_QUOTES, 'UTF-8') ?>">
                        </label>
                        <label class="sandbox-form__label">
                            Padding Unit
                            <span class="sandbox-form__help">Unita del padding di sezione.</span>
                            <select name="slot_padding_unit" class="sandbox-form__control">
                                <?php foreach (['px', 'percent'] as $unit): ?>
                                    <option value="<?= htmlspecialchars($unit, ENT_QUOTES, 'UTF-8') ?>"<?= (($selectedSlotSettings['padding_unit'] ?? 'px') === $unit) ? ' selected' : '' ?>>
                                        <?= htmlspecialchars($unit, ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label class="sandbox-form__label">
                            Gap Interno
                            <span class="sandbox-form__help">Spazio tra i componenti contenuti nello slot.</span>
                            <input class="sandbox-form__control" type="number" step="1" name="slot_gap_value" value="<?= htmlspecialchars((string) ($selectedSlotSettings['gap_value'] ?? 12), ENT_QUOTES, 'UTF-8') ?>">
                        </label>
                        <label class="sandbox-form__label">
                            Gap Unit
                            <span class="sandbox-form__help">Unita del gap interno della sezione.</span>
                            <select name="slot_gap_unit" class="sandbox-form__control">
                                <?php foreach (['px', 'percent'] as $unit): ?>
                                    <option value="<?= htmlspecialchars($unit, ENT_QUOTES, 'UTF-8') ?>"<?= (($selectedSlotSettings['gap_unit'] ?? 'px') === $unit) ? ' selected' : '' ?>>
                                        <?= htmlspecialchars($unit, ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label class="sandbox-form__label">
                            Visibilita
                            <span class="sandbox-form__help">Mostra, condiziona o nasconde l'intera sezione.</span>
                            <select name="slot_visibility_mode" class="sandbox-form__control">
                                <?php foreach (['visible', 'conditional', 'hidden'] as $visibilityMode): ?>
                                    <option value="<?= htmlspecialchars($visibilityMode, ENT_QUOTES, 'UTF-8') ?>"<?= (($selectedSlotSettings['visibility_mode'] ?? 'visible') === $visibilityMode) ? ' selected' : '' ?>>
                                        <?= htmlspecialchars($visibilityMode, ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <button class="sandbox-action" type="submit">Salva opzioni sezione</button>
                    </form>
                <?php endif; ?>

                <?php if ($selectionMode === 'binding'): ?>
                    <p class="sandbox-kicker">Proprieta Binding</p>
                    <form class="sandbox-form" method="get" action="<?= htmlspecialchars($basePath . '/sandbox', ENT_QUOTES, 'UTF-8') ?>" data-sandbox-sidebar-focus data-sandbox-preview-form="binding">
                        <input type="hidden" name="model" value="<?= htmlspecialchars((string) $modelKey, ENT_QUOTES, 'UTF-8') ?>">
                        <input type="hidden" name="version" value="<?= htmlspecialchars((string) $currentVersionNo, ENT_QUOTES, 'UTF-8') ?>">
                        <input type="hidden" name="action" value="update_binding">
                        <input type="hidden" name="binding_id" value="<?= htmlspecialchars((string) ($selectedBinding['id'] ?? 0), ENT_QUOTES, 'UTF-8') ?>">
                        <input type="hidden" name="selected_binding_focus" value="binding">
                        <label class="sandbox-form__label">
                            Width
                            <span class="sandbox-form__help">Larghezza principale del componente dentro lo slot.</span>
                            <input class="sandbox-form__control" type="number" step="1" name="width_value" value="<?= htmlspecialchars((string) ($selectedBinding['width_value'] ?? 100), ENT_QUOTES, 'UTF-8') ?>">
                        </label>
                        <label class="sandbox-form__label">
                            Unit
                            <span class="sandbox-form__help">Percentuale per adattarsi allo slot, pixel per una misura rigida.</span>
                            <select name="width_unit" class="sandbox-form__control">
                                <?php foreach (['percent', 'px'] as $unit): ?>
                                    <option value="<?= htmlspecialchars($unit, ENT_QUOTES, 'UTF-8') ?>"<?= (($selectedBinding['width_unit'] ?? 'percent') === $unit) ? ' selected' : '' ?>>
                                        <?= htmlspecialchars($unit, ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label class="sandbox-form__label">
                            Height
                            <span class="sandbox-form__help">Altezza minima del componente nella preview.</span>
                            <input class="sandbox-form__control" type="number" step="1" name="height_value" value="<?= htmlspecialchars((string) ($selectedBinding['height_value'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                        </label>
                        <label class="sandbox-form__label">
                            Height Unit
                            <span class="sandbox-form__help">Unita dell'altezza minima del componente.</span>
                            <select name="height_unit" class="sandbox-form__control">
                                <?php foreach (['percent', 'px'] as $unit): ?>
                                    <option value="<?= htmlspecialchars($unit, ENT_QUOTES, 'UTF-8') ?>"<?= (($selectedBinding['height_unit'] ?? 'percent') === $unit) ? ' selected' : '' ?>>
                                        <?= htmlspecialchars($unit, ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label class="sandbox-form__label">
                            Min Width
                            <span class="sandbox-form__help">Larghezza minima sotto la quale il componente non deve scendere.</span>
                            <input class="sandbox-form__control" type="number" step="1" name="min_width_value" value="<?= htmlspecialchars((string) (($selectedBinding['media_config_json']['layout']['min_width_value'] ?? '')), ENT_QUOTES, 'UTF-8') ?>">
                        </label>
                        <label class="sandbox-form__label">
                            Min Width Unit
                            <span class="sandbox-form__help">Unita del limite minimo di larghezza.</span>
                            <select name="min_width_unit" class="sandbox-form__control">
                                <?php foreach (['px', 'percent'] as $unit): ?>
                                    <option value="<?= htmlspecialchars($unit, ENT_QUOTES, 'UTF-8') ?>"<?= ((($selectedBinding['media_config_json']['layout']['min_width_unit'] ?? 'px')) === $unit) ? ' selected' : '' ?>>
                                        <?= htmlspecialchars($unit, ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label class="sandbox-form__label">
                            Max Width
                            <span class="sandbox-form__help">Larghezza massima del componente quando lo slot e ampio.</span>
                            <input class="sandbox-form__control" type="number" step="1" name="max_width_value" value="<?= htmlspecialchars((string) (($selectedBinding['media_config_json']['layout']['max_width_value'] ?? '')), ENT_QUOTES, 'UTF-8') ?>">
                        </label>
                        <label class="sandbox-form__label">
                            Max Width Unit
                            <span class="sandbox-form__help">Unita del limite massimo di larghezza.</span>
                            <select name="max_width_unit" class="sandbox-form__control">
                                <?php foreach (['percent', 'px'] as $unit): ?>
                                    <option value="<?= htmlspecialchars($unit, ENT_QUOTES, 'UTF-8') ?>"<?= ((($selectedBinding['media_config_json']['layout']['max_width_unit'] ?? 'percent')) === $unit) ? ' selected' : '' ?>>
                                        <?= htmlspecialchars($unit, ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label class="sandbox-form__label">
                            Padding
                            <span class="sandbox-form__help">Spazio interno del box del componente.</span>
                            <input class="sandbox-form__control" type="number" step="1" name="padding_value" value="<?= htmlspecialchars((string) (($selectedBinding['media_config_json']['layout']['padding_value'] ?? '')), ENT_QUOTES, 'UTF-8') ?>">
                        </label>
                        <label class="sandbox-form__label">
                            Padding Unit
                            <span class="sandbox-form__help">Unita del padding interno del componente.</span>
                            <select name="padding_unit" class="sandbox-form__control">
                                <?php foreach (['px', 'percent'] as $unit): ?>
                                    <option value="<?= htmlspecialchars($unit, ENT_QUOTES, 'UTF-8') ?>"<?= ((($selectedBinding['media_config_json']['layout']['padding_unit'] ?? 'px')) === $unit) ? ' selected' : '' ?>>
                                        <?= htmlspecialchars($unit, ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label class="sandbox-form__label">
                            Inner Gap
                            <span class="sandbox-form__help">Spazio tra titolo, meta, chip e altri elementi interni della card.</span>
                            <input class="sandbox-form__control" type="number" step="1" name="gap_value" value="<?= htmlspecialchars((string) (($selectedBinding['media_config_json']['layout']['gap_value'] ?? '')), ENT_QUOTES, 'UTF-8') ?>">
                        </label>
                        <label class="sandbox-form__label">
                            Gap Unit
                            <span class="sandbox-form__help">Unita del gap interno del componente.</span>
                            <select name="gap_unit" class="sandbox-form__control">
                                <?php foreach (['px', 'percent'] as $unit): ?>
                                    <option value="<?= htmlspecialchars($unit, ENT_QUOTES, 'UTF-8') ?>"<?= ((($selectedBinding['media_config_json']['layout']['gap_unit'] ?? 'px')) === $unit) ? ' selected' : '' ?>>
                                        <?= htmlspecialchars($unit, ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label class="sandbox-form__label">
                            Border Radius
                            <span class="sandbox-form__help">Arrotondamento degli angoli del box componente.</span>
                            <input class="sandbox-form__control" type="number" step="1" name="border_radius_value" value="<?= htmlspecialchars((string) (($selectedBinding['media_config_json']['layout']['border_radius_value'] ?? '')), ENT_QUOTES, 'UTF-8') ?>">
                        </label>
                        <label class="sandbox-form__label">
                            Radius Unit
                            <span class="sandbox-form__help">Unita usata per l'arrotondamento.</span>
                            <select name="border_radius_unit" class="sandbox-form__control">
                                <?php foreach (['px', 'percent'] as $unit): ?>
                                    <option value="<?= htmlspecialchars($unit, ENT_QUOTES, 'UTF-8') ?>"<?= ((($selectedBinding['media_config_json']['layout']['border_radius_unit'] ?? 'px')) === $unit) ? ' selected' : '' ?>>
                                        <?= htmlspecialchars($unit, ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label class="sandbox-form__label">
                            Background Mode
                            <span class="sandbox-form__help">Tipo di fondo del box componente.</span>
                            <select name="background_mode" class="sandbox-form__control">
                                <?php foreach (['none', 'solid', 'gradient', 'image', 'gradient_image'] as $mode): ?>
                                    <option value="<?= htmlspecialchars($mode, ENT_QUOTES, 'UTF-8') ?>"<?= ((($selectedBinding['media_config_json']['layout']['background_mode'] ?? 'none')) === $mode) ? ' selected' : '' ?>>
                                        <?= htmlspecialchars($mode, ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label class="sandbox-form__label">
                            Background Color
                            <span class="sandbox-form__help">Colore principale del componente quando usi un fondo pieno o misto.</span>
                            <input class="sandbox-form__control" type="text" name="background_color" value="<?= htmlspecialchars((string) (($selectedBinding['media_config_json']['layout']['background_color'] ?? '')), ENT_QUOTES, 'UTF-8') ?>" placeholder="#222222 or rgba(...)">
                        </label>
                        <label class="sandbox-form__label">
                            Gradient From
                            <span class="sandbox-form__help">Primo colore del gradiente del componente.</span>
                            <input class="sandbox-form__control" type="text" name="background_gradient_from" value="<?= htmlspecialchars((string) (($selectedBinding['media_config_json']['layout']['background_gradient_from'] ?? '')), ENT_QUOTES, 'UTF-8') ?>" placeholder="#222222">
                        </label>
                        <label class="sandbox-form__label">
                            Gradient To
                            <span class="sandbox-form__help">Secondo colore del gradiente del componente.</span>
                            <input class="sandbox-form__control" type="text" name="background_gradient_to" value="<?= htmlspecialchars((string) (($selectedBinding['media_config_json']['layout']['background_gradient_to'] ?? '')), ENT_QUOTES, 'UTF-8') ?>" placeholder="#111111">
                        </label>
                        <label class="sandbox-form__label">
                            Gradient Angle
                            <span class="sandbox-form__help">Angolo del gradiente del box componente.</span>
                            <input class="sandbox-form__control" type="number" step="1" name="background_gradient_angle" value="<?= htmlspecialchars((string) (($selectedBinding['media_config_json']['layout']['background_gradient_angle'] ?? 135)), ENT_QUOTES, 'UTF-8') ?>">
                        </label>
                        <label class="sandbox-form__label">
                            Image URL
                            <span class="sandbox-form__help">Immagine di sfondo applicata al box del componente.</span>
                            <input class="sandbox-form__control" type="text" name="background_image_url" value="<?= htmlspecialchars((string) (($selectedBinding['media_config_json']['layout']['background_image_url'] ?? '')), ENT_QUOTES, 'UTF-8') ?>" placeholder="https://...">
                        </label>
                        <label class="sandbox-form__label">
                            Background Position
                            <span class="sandbox-form__help">Punto di ancoraggio dell'immagine di sfondo del componente.</span>
                            <input class="sandbox-form__control" type="text" name="background_position" value="<?= htmlspecialchars((string) (($selectedBinding['media_config_json']['layout']['background_position'] ?? 'center center')), ENT_QUOTES, 'UTF-8') ?>" placeholder="center center">
                        </label>
                        <label class="sandbox-form__label">
                            Background Size
                            <span class="sandbox-form__help">Come l'immagine di sfondo occupa il box componente.</span>
                            <select name="background_size" class="sandbox-form__control">
                                <?php foreach (['cover', 'contain', 'auto', '100% 100%'] as $size): ?>
                                    <option value="<?= htmlspecialchars($size, ENT_QUOTES, 'UTF-8') ?>"<?= ((($selectedBinding['media_config_json']['layout']['background_size'] ?? 'cover')) === $size) ? ' selected' : '' ?>>
                                        <?= htmlspecialchars($size, ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label class="sandbox-form__label">
                            Background Repeat
                            <span class="sandbox-form__help">Ripetizione del fondo del componente.</span>
                            <select name="background_repeat" class="sandbox-form__control">
                                <?php foreach (['no-repeat', 'repeat', 'repeat-x', 'repeat-y'] as $repeat): ?>
                                    <option value="<?= htmlspecialchars($repeat, ENT_QUOTES, 'UTF-8') ?>"<?= ((($selectedBinding['media_config_json']['layout']['background_repeat'] ?? 'no-repeat')) === $repeat) ? ' selected' : '' ?>>
                                        <?= htmlspecialchars($repeat, ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label class="sandbox-form__label">
                            Background Attachment
                            <span class="sandbox-form__help">Gestisce lo scorrimento del fondo del componente.</span>
                            <select name="background_attachment" class="sandbox-form__control">
                                <?php foreach (['scroll', 'fixed', 'local'] as $attachment): ?>
                                    <option value="<?= htmlspecialchars($attachment, ENT_QUOTES, 'UTF-8') ?>"<?= ((($selectedBinding['media_config_json']['layout']['background_attachment'] ?? 'scroll')) === $attachment) ? ' selected' : '' ?>>
                                        <?= htmlspecialchars($attachment, ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label class="sandbox-form__label">
                            Blend Mode
                            <span class="sandbox-form__help">Fusione tra colore, gradiente e immagine del fondo componente.</span>
                            <select name="background_blend_mode" class="sandbox-form__control">
                                <?php foreach (['normal', 'multiply', 'screen', 'overlay', 'soft-light', 'hard-light'] as $blendMode): ?>
                                    <option value="<?= htmlspecialchars($blendMode, ENT_QUOTES, 'UTF-8') ?>"<?= ((($selectedBinding['media_config_json']['layout']['background_blend_mode'] ?? 'normal')) === $blendMode) ? ' selected' : '' ?>>
                                        <?= htmlspecialchars($blendMode, ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label class="sandbox-form__label">
                            Alignment
                            <span class="sandbox-form__help">Allineamento del componente rispetto all'asse dello slot.</span>
                            <select name="alignment" class="sandbox-form__control">
                                <?php foreach (['start', 'center', 'end', 'stretch'] as $alignment): ?>
                                    <option value="<?= htmlspecialchars($alignment, ENT_QUOTES, 'UTF-8') ?>"<?= (($selectedBinding['alignment'] ?? 'start') === $alignment) ? ' selected' : '' ?>>
                                        <?= htmlspecialchars($alignment, ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label class="sandbox-form__label">
                            Visibility
                            <span class="sandbox-form__help">Mostra sempre il componente, rendilo condizionale o nascondilo.</span>
                            <select name="visibility_mode" class="sandbox-form__control">
                                <?php $currentVisibility = $selectedBinding['visibility_rule']['mode'] ?? 'always'; ?>
                                <?php foreach (['always', 'conditional', 'hidden'] as $mode): ?>
                                    <option value="<?= htmlspecialchars($mode, ENT_QUOTES, 'UTF-8') ?>"<?= ($currentVisibility === $mode) ? ' selected' : '' ?>>
                                        <?= htmlspecialchars($mode, ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label class="sandbox-form__label">
                            Fallback
                            <span class="sandbox-form__help">Valore di sicurezza da usare quando il dato principale manca.</span>
                            <input class="sandbox-form__control" type="text" name="fallback_value" value="<?= htmlspecialchars((string) ($selectedBinding['fallback_value'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                        </label>
                        <div class="sandbox-form__actions">
                            <button class="sandbox-action" type="submit">Salva proprieta</button>
                            <a
                                class="sandbox-action sandbox-action--ghost"
                                href="<?= htmlspecialchars($buildSandboxUrl([
                                    'action' => 'delete_binding',
                                    'binding_id' => $selectedBinding['id'] ?? null,
                                    'selected_slot' => $selectedBinding['slot_key'] ?? $selectedSlot,
                                ]), ENT_QUOTES, 'UTF-8') ?>"
                            >
                                Elimina componente
                            </a>
                        </div>
                    </form>
                <?php endif; ?>

                <?php if ($selectionMode === 'media'): ?>
                    <p class="sandbox-kicker">Opzioni Immagine</p>
                    <form class="sandbox-form" method="get" action="<?= htmlspecialchars($basePath . '/sandbox', ENT_QUOTES, 'UTF-8') ?>" data-sandbox-sidebar-focus data-sandbox-preview-form="binding">
                        <input type="hidden" name="model" value="<?= htmlspecialchars((string) $modelKey, ENT_QUOTES, 'UTF-8') ?>">
                        <input type="hidden" name="version" value="<?= htmlspecialchars((string) $currentVersionNo, ENT_QUOTES, 'UTF-8') ?>">
                        <input type="hidden" name="action" value="update_binding">
                        <input type="hidden" name="binding_id" value="<?= htmlspecialchars((string) ($selectedBinding['id'] ?? 0), ENT_QUOTES, 'UTF-8') ?>">
                        <input type="hidden" name="selected_binding_focus" value="media">
                        <label class="sandbox-form__label">
                            Preview Media URL
                            <span class="sandbox-form__help">Immagine di anteprima usata per simulare un modulo media nella sandbox.</span>
                            <input class="sandbox-form__control" type="text" name="preview_media_url" value="<?= htmlspecialchars((string) (($selectedBinding['media_config_json']['media']['preview_media_url'] ?? '')), ENT_QUOTES, 'UTF-8') ?>" placeholder="https://...">
                        </label>
                        <label class="sandbox-form__label">
                            Media Fit
                            <span class="sandbox-form__help">Decide se l'immagine copre, contiene o si deforma per riempire il riquadro.</span>
                            <select name="media_fit_mode" class="sandbox-form__control">
                                <?php foreach (['cover', 'contain', 'fill'] as $fit): ?>
                                    <option value="<?= htmlspecialchars($fit, ENT_QUOTES, 'UTF-8') ?>"<?= ((($selectedBinding['media_config_json']['media']['fit_mode'] ?? 'cover')) === $fit) ? ' selected' : '' ?>>
                                        <?= htmlspecialchars($fit, ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label class="sandbox-form__label">
                            Ratio
                            <span class="sandbox-form__help">Proporzione del riquadro media: widescreen, quadrato, verticale o automatico.</span>
                            <select name="media_ratio" class="sandbox-form__control">
                                <?php foreach (['auto', '21:9', '16:9', '4:3', '1:1', '3:4', '9:16'] as $ratio): ?>
                                    <option value="<?= htmlspecialchars($ratio, ENT_QUOTES, 'UTF-8') ?>"<?= ((($selectedBinding['media_config_json']['media']['ratio'] ?? 'auto')) === $ratio) ? ' selected' : '' ?>>
                                        <?= htmlspecialchars($ratio, ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label class="sandbox-form__label">
                            Focus X
                            <span class="sandbox-form__help">Punto orizzontale importante dell'immagine, da 0 a 100.</span>
                            <input class="sandbox-form__control" type="number" min="0" max="100" step="1" name="media_focus_x" value="<?= htmlspecialchars((string) (($selectedBinding['media_config_json']['media']['focus_x'] ?? 50)), ENT_QUOTES, 'UTF-8') ?>">
                        </label>
                        <label class="sandbox-form__label">
                            Focus Y
                            <span class="sandbox-form__help">Punto verticale importante dell'immagine, da 0 a 100.</span>
                            <input class="sandbox-form__control" type="number" min="0" max="100" step="1" name="media_focus_y" value="<?= htmlspecialchars((string) (($selectedBinding['media_config_json']['media']['focus_y'] ?? 50)), ENT_QUOTES, 'UTF-8') ?>">
                        </label>
                        <div class="sandbox-form__actions">
                            <button class="sandbox-action" type="submit">Salva proprieta</button>
                            <a
                                class="sandbox-action sandbox-action--ghost"
                                href="<?= htmlspecialchars($buildSandboxUrl([
                                    'action' => 'delete_binding',
                                    'binding_id' => $selectedBinding['id'] ?? null,
                                    'selected_slot' => $selectedBinding['slot_key'] ?? $selectedSlot,
                                ]), ENT_QUOTES, 'UTF-8') ?>"
                            >
                                Elimina componente
                            </a>
                        </div>
                    </form>
                <?php endif; ?>
            </aside>
        </section>
    </main>

    <script src="<?= htmlspecialchars($basePath . '/assets/js/system/base.js', ENT_QUOTES, 'UTF-8') ?>"></script>
    <script src="<?= htmlspecialchars($basePath . '/assets/js/sandbox/preview.js', ENT_QUOTES, 'UTF-8') ?>"></script>
</body>
</html>
