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
$styleProfile = $sandbox['style_profile'] ?? [];
$selectedSlot = $sandbox['selected_slot'] ?? null;
$modelKey = $sandbox['model_key'] ?? 'homepage_lab';
$gridMode = $version['structure_json']['grid_mode'] ?? 'snap_soft';
$selectedBinding = $sandbox['selected_binding'] ?? null;
$selectedBindingId = $sandbox['selected_binding_id'] ?? null;
$versions = $sandbox['versions'] ?? [];
$currentVersionNo = $version['version_no'] ?? null;
$selectedSourceKey = $sandbox['selected_source_key'] ?? null;
$selectedSource = $sandbox['selected_source'] ?? null;
$selectedFieldKey = $_GET['field_key'] ?? ($selectedSource['fields'][0]['field_key'] ?? null);
$selectedFieldLabel = null;
$bindingsBySlot = [];

foreach (($sandbox['bindings'] ?? []) as $binding) {
    $bindingsBySlot[(string) $binding['slot_key']][] = $binding;
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
<body>
    <main class="sandbox-shell">
        <header class="sandbox-header">
            <p class="sandbox-kicker">Sandbox Project</p>
            <h1 class="sandbox-title"><?= htmlspecialchars((string) $pageTitle, ENT_QUOTES, 'UTF-8') ?></h1>
            <?php if (!empty($sandbox['notes'])): ?>
                <p class="sandbox-copy"><?= htmlspecialchars((string) $sandbox['notes'], ENT_QUOTES, 'UTF-8') ?></p>
            <?php endif; ?>
        </header>

        <section class="sandbox-grid">
            <aside class="sandbox-panel">
                <p class="sandbox-kicker">Modello</p>
                <p class="sandbox-meta">Tipo: <?= htmlspecialchars((string) ($sandbox['model_type'] ?? ''), ENT_QUOTES, 'UTF-8') ?></p>
                <p class="sandbox-meta">Stato: <?= htmlspecialchars((string) ($sandbox['status'] ?? ''), ENT_QUOTES, 'UTF-8') ?></p>
                <p class="sandbox-meta">Versione: <?= htmlspecialchars((string) $currentVersionNo, ENT_QUOTES, 'UTF-8') ?></p>
                <a class="sandbox-action sandbox-action--ghost" href="<?= htmlspecialchars($basePath . '/sandbox?model=' . urlencode((string) $modelKey) . '&version=' . urlencode((string) $currentVersionNo) . '&action=clone_version', ENT_QUOTES, 'UTF-8') ?>">
                    Nuova Versione
                </a>

                <p class="sandbox-kicker">Versioni</p>
                <ul class="sandbox-list">
                    <?php foreach ($versions as $item): ?>
                        <li>
                            <a href="<?= htmlspecialchars($basePath . '/sandbox?model=' . urlencode((string) $modelKey) . '&version=' . urlencode((string) $item['version_no']), ENT_QUOTES, 'UTF-8') ?>">
                                v<?= htmlspecialchars((string) $item['version_no'], ENT_QUOTES, 'UTF-8') ?>
                            </a>
                            <?php if ((int) $currentVersionNo === (int) $item['version_no']): ?>
                                <span class="sandbox-field-chip">active</span>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <p class="sandbox-kicker">Libreria Modelli</p>
                <ul class="sandbox-list">
                    <?php foreach ($models as $item): ?>
                        <li>
                            <a href="<?= htmlspecialchars($basePath . '/sandbox?model=' . urlencode((string) $item['model_key']), ENT_QUOTES, 'UTF-8') ?>">
                                <?= htmlspecialchars((string) $item['name'], ENT_QUOTES, 'UTF-8') ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <p class="sandbox-kicker">Nuovo Modello</p>
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
                        <input class="sandbox-form__control" type="text" name="new_model_slots" value="hero, main, aside">
                    </label>
                    <button class="sandbox-action" type="submit">Crea Modello</button>
                </form>

                <p class="sandbox-kicker">Slot</p>
                <ul class="sandbox-list">
                    <?php foreach ($slots as $slot): ?>
                        <li>
                            <a href="<?= htmlspecialchars($basePath . '/sandbox?model=' . urlencode((string) $modelKey) . '&version=' . urlencode((string) $currentVersionNo) . '&action=update_state&selected_slot=' . urlencode((string) $slot), ENT_QUOTES, 'UTF-8') ?>">
                                <?= htmlspecialchars((string) $slot, ENT_QUOTES, 'UTF-8') ?>
                            </a>
                            <?php if ($selectedSlot === $slot): ?>
                                <span class="sandbox-field-chip">active</span>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <p class="sandbox-kicker">Griglia</p>
                <ul class="sandbox-list">
                    <?php foreach (['snap_strong', 'snap_soft', 'snap_off'] as $mode): ?>
                        <li>
                            <a href="<?= htmlspecialchars($basePath . '/sandbox?model=' . urlencode((string) $modelKey) . '&version=' . urlencode((string) $currentVersionNo) . '&action=update_state&grid_mode=' . urlencode($mode), ENT_QUOTES, 'UTF-8') ?>">
                                <?= htmlspecialchars($mode, ENT_QUOTES, 'UTF-8') ?>
                            </a>
                            <?php if ($gridMode === $mode): ?>
                                <span class="sandbox-field-chip">active</span>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </aside>

            <section class="sandbox-canvas" data-sandbox-preview>
                <div class="sandbox-stage" data-grid-mode="<?= htmlspecialchars((string) $gridMode, ENT_QUOTES, 'UTF-8') ?>">
                    <p class="sandbox-kicker">Preview</p>
                    <h2 class="sandbox-title"><?= htmlspecialchars((string) ($preview['title'] ?? ''), ENT_QUOTES, 'UTF-8') ?></h2>
                    <p class="sandbox-copy"><?= htmlspecialchars((string) ($preview['subtitle'] ?? ''), ENT_QUOTES, 'UTF-8') ?></p>
                    <?php foreach (($preview['placeholders'] ?? []) as $placeholder): ?>
                        <?php $placeholderSlot = $placeholder['slot'] ?? null; ?>
                        <div class="sandbox-placeholder<?= $selectedSlot === $placeholderSlot ? ' is-selected' : '' ?>">
                            <strong><?= htmlspecialchars((string) ($placeholder['label'] ?? ''), ENT_QUOTES, 'UTF-8') ?></strong>
                            <p><?= htmlspecialchars((string) ($placeholder['hint'] ?? ''), ENT_QUOTES, 'UTF-8') ?></p>
                            <?php if (!empty($placeholder['slot'])): ?>
                                <span class="sandbox-field-chip"><?= htmlspecialchars((string) $placeholder['slot'], ENT_QUOTES, 'UTF-8') ?></span>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <aside class="sandbox-preview">
                <p class="sandbox-kicker">Contratto Dati</p>
                <ul class="sandbox-list">
                    <?php foreach (($contract['rules'] ?? []) as $rule): ?>
                        <li><?= htmlspecialchars((string) $rule, ENT_QUOTES, 'UTF-8') ?></li>
                    <?php endforeach; ?>
                </ul>

                <p class="sandbox-kicker">Binding</p>
                <ul class="sandbox-list">
                    <?php foreach ($sandbox['bindings'] as $binding): ?>
                        <li>
                            <a href="<?= htmlspecialchars($basePath . '/sandbox?model=' . urlencode((string) $modelKey) . '&version=' . urlencode((string) $currentVersionNo) . '&action=select_binding&binding_id=' . urlencode((string) $binding['id']) . '&selected_slot=' . urlencode((string) $binding['slot_key']), ENT_QUOTES, 'UTF-8') ?>">
                                <?= htmlspecialchars((string) $binding['field_key'], ENT_QUOTES, 'UTF-8') ?>
                                &middot;
                                <?= htmlspecialchars((string) $binding['slot_key'], ENT_QUOTES, 'UTF-8') ?>
                            </a>
                            <?php if ((int) $selectedBindingId === (int) $binding['id']): ?>
                                <span class="sandbox-field-chip">active</span>
                            <?php endif; ?>
                            <span class="sandbox-inline-actions">
                                <a class="sandbox-inline-link" href="<?= htmlspecialchars($basePath . '/sandbox?model=' . urlencode((string) $modelKey) . '&version=' . urlencode((string) $currentVersionNo) . '&action=move_binding&binding_id=' . urlencode((string) $binding['id']) . '&direction=up', ENT_QUOTES, 'UTF-8') ?>">up</a>
                                <a class="sandbox-inline-link" href="<?= htmlspecialchars($basePath . '/sandbox?model=' . urlencode((string) $modelKey) . '&version=' . urlencode((string) $currentVersionNo) . '&action=move_binding&binding_id=' . urlencode((string) $binding['id']) . '&direction=down', ENT_QUOTES, 'UTF-8') ?>">down</a>
                                <a class="sandbox-inline-link sandbox-inline-link--danger" href="<?= htmlspecialchars($basePath . '/sandbox?model=' . urlencode((string) $modelKey) . '&version=' . urlencode((string) $currentVersionNo) . '&action=delete_binding&binding_id=' . urlencode((string) $binding['id']), ENT_QUOTES, 'UTF-8') ?>">remove</a>
                            </span>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <p class="sandbox-kicker">Proprieta Slot</p>
                <ul class="sandbox-list">
                    <?php foreach (($bindingsBySlot[$selectedSlot] ?? []) as $binding): ?>
                        <li>
                            <?= htmlspecialchars((string) $binding['field_alias'], ENT_QUOTES, 'UTF-8') ?>
                            &middot;
                            <?= htmlspecialchars((string) $binding['bind_type'], ENT_QUOTES, 'UTF-8') ?>
                            &middot;
                            <?= htmlspecialchars((string) ($binding['width_value'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                            <?= htmlspecialchars((string) ($binding['width_unit'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                        </li>
                    <?php endforeach; ?>
                    <?php if (empty($bindingsBySlot[$selectedSlot] ?? [])): ?>
                        <li>Nessun binding sullo slot selezionato.</li>
                    <?php endif; ?>
                </ul>

                <p class="sandbox-kicker">Stile</p>
                <ul class="sandbox-list">
                    <?php foreach ($styleProfile as $key => $value): ?>
                        <li><?= htmlspecialchars((string) $key, ENT_QUOTES, 'UTF-8') ?> &middot; <?= htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8') ?></li>
                    <?php endforeach; ?>
                </ul>

                <p class="sandbox-kicker">Sorgenti</p>
                <ul class="sandbox-list">
                    <?php foreach ($sources as $source): ?>
                        <li>
                            <?= htmlspecialchars((string) $source['label'], ENT_QUOTES, 'UTF-8') ?>
                            <?php if (!empty($source['fields'])): ?>
                                <div class="sandbox-source-fields">
                                    <?php foreach ($source['fields'] as $field): ?>
                                        <span class="sandbox-field-chip"><?= htmlspecialchars((string) $field['field_key'], ENT_QUOTES, 'UTF-8') ?></span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <p class="sandbox-kicker">Nuovo Binding</p>
                <form class="sandbox-form" method="get" action="<?= htmlspecialchars($basePath . '/sandbox', ENT_QUOTES, 'UTF-8') ?>">
                    <input type="hidden" name="model" value="<?= htmlspecialchars((string) $modelKey, ENT_QUOTES, 'UTF-8') ?>">
                    <input type="hidden" name="version" value="<?= htmlspecialchars((string) $currentVersionNo, ENT_QUOTES, 'UTF-8') ?>">
                    <label class="sandbox-form__label">
                        Slot
                        <select name="slot_key" class="sandbox-form__control">
                            <?php foreach ($slots as $slot): ?>
                                <option value="<?= htmlspecialchars((string) $slot, ENT_QUOTES, 'UTF-8') ?>"<?= $selectedSlot === $slot ? ' selected' : '' ?>>
                                    <?= htmlspecialchars((string) $slot, ENT_QUOTES, 'UTF-8') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                    <label class="sandbox-form__label">
                        Sorgente
                        <select name="data_source_key" class="sandbox-form__control">
                            <?php foreach ($sources as $source): ?>
                                <option value="<?= htmlspecialchars((string) $source['source_key'], ENT_QUOTES, 'UTF-8') ?>"<?= ($selectedSourceKey === ($source['source_key'] ?? null)) ? ' selected' : '' ?>>
                                    <?= htmlspecialchars((string) $source['label'], ENT_QUOTES, 'UTF-8') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                    <label class="sandbox-form__label">
                        Campo
                        <select name="field_key" class="sandbox-form__control" data-sandbox-field-select>
                            <?php foreach (($selectedSource['fields'] ?? []) as $field): ?>
                                <option value="<?= htmlspecialchars((string) $field['field_key'], ENT_QUOTES, 'UTF-8') ?>"<?= ($selectedFieldKey === ($field['field_key'] ?? null)) ? ' selected' : '' ?>>
                                    <?= htmlspecialchars((string) $field['field_key'], ENT_QUOTES, 'UTF-8') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                    <label class="sandbox-form__label">
                        Alias
                        <input class="sandbox-form__control" type="text" name="field_alias" value="<?= htmlspecialchars((string) $selectedFieldLabel, ENT_QUOTES, 'UTF-8') ?>" data-sandbox-alias-input>
                    </label>
                    <div class="sandbox-form__actions">
                        <button class="sandbox-action sandbox-action--ghost" type="submit" name="source_preview" value="1">Aggiorna campi</button>
                        <button class="sandbox-action" type="submit" name="action" value="create_binding">Aggiungi binding</button>
                    </div>
                </form>

                <p class="sandbox-kicker">Proprieta Binding</p>
                <form class="sandbox-form" method="get" action="<?= htmlspecialchars($basePath . '/sandbox', ENT_QUOTES, 'UTF-8') ?>">
                    <input type="hidden" name="model" value="<?= htmlspecialchars((string) $modelKey, ENT_QUOTES, 'UTF-8') ?>">
                    <input type="hidden" name="version" value="<?= htmlspecialchars((string) $currentVersionNo, ENT_QUOTES, 'UTF-8') ?>">
                    <input type="hidden" name="action" value="update_binding">
                    <input type="hidden" name="binding_id" value="<?= htmlspecialchars((string) ($selectedBinding['id'] ?? 0), ENT_QUOTES, 'UTF-8') ?>">
                    <label class="sandbox-form__label">
                        Width
                        <input class="sandbox-form__control" type="number" step="1" name="width_value" value="<?= htmlspecialchars((string) ($selectedBinding['width_value'] ?? 100), ENT_QUOTES, 'UTF-8') ?>">
                    </label>
                    <label class="sandbox-form__label">
                        Unit
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
                        <input class="sandbox-form__control" type="number" step="1" name="height_value" value="<?= htmlspecialchars((string) ($selectedBinding['height_value'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                    </label>
                    <label class="sandbox-form__label">
                        Height Unit
                        <select name="height_unit" class="sandbox-form__control">
                            <?php foreach (['percent', 'px'] as $unit): ?>
                                <option value="<?= htmlspecialchars($unit, ENT_QUOTES, 'UTF-8') ?>"<?= (($selectedBinding['height_unit'] ?? 'percent') === $unit) ? ' selected' : '' ?>>
                                    <?= htmlspecialchars($unit, ENT_QUOTES, 'UTF-8') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                    <label class="sandbox-form__label">
                        Alignment
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
                        <input class="sandbox-form__control" type="text" name="fallback_value" value="<?= htmlspecialchars((string) ($selectedBinding['fallback_value'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                    </label>
                    <button class="sandbox-action" type="submit">Salva proprieta</button>
                </form>
            </aside>
        </section>
    </main>

    <script src="<?= htmlspecialchars($basePath . '/assets/js/system/base.js', ENT_QUOTES, 'UTF-8') ?>"></script>
    <script src="<?= htmlspecialchars($basePath . '/assets/js/sandbox/preview.js', ENT_QUOTES, 'UTF-8') ?>"></script>
</body>
</html>
