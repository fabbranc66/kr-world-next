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

function sandbox_normalize_model_key(string $value): string
{
    $value = strtolower(trim($value));
    $value = preg_replace('/[^a-z0-9]+/', '_', $value) ?? '';

    return trim($value, '_');
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

    return $slots !== [] ? $slots : ['hero', 'main', 'aside'];
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
    $slots = sandbox_parse_slots((string) ($input['new_model_slots'] ?? 'hero, main, aside'));

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
            'label' => ucfirst(str_replace('_', ' ', $slot)),
            'hint' => 'Slot pronto per binding reali.',
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
        'SELECT source_key, label, source_type
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

    $selectedSlot = (string) ($input['selected_slot'] ?? '');
    if ($selectedSlot !== '' && in_array($selectedSlot, $structure['frame'] ?? [], true)) {
        $structure['selected_slot'] = $selectedSlot;
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
        }
    } elseif (array_key_exists('selected_binding_id', $input)) {
        unset($structure['selected_binding_id']);
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

    $widthValue = is_numeric($input['width_value'] ?? null) ? (float) $input['width_value'] : 100.0;
    $widthUnit = (string) ($input['width_unit'] ?? 'percent');
    $heightValue = is_numeric($input['height_value'] ?? null) ? (float) $input['height_value'] : null;
    $heightUnit = (string) ($input['height_unit'] ?? 'percent');
    $alignment = (string) ($input['alignment'] ?? 'start');
    $fallbackValue = (string) ($input['fallback_value'] ?? '');
    $visibilityMode = (string) ($input['visibility_mode'] ?? 'always');

    if (!in_array($widthUnit, ['px', 'percent'], true)) {
        $widthUnit = 'percent';
    }

    if (!in_array($heightUnit, ['px', 'percent'], true)) {
        $heightUnit = 'percent';
    }

    if (!in_array($alignment, sandbox_allowed_alignments(), true)) {
        $alignment = 'start';
    }

    if (!in_array($visibilityMode, ['always', 'conditional', 'hidden'], true)) {
        $visibilityMode = 'always';
    }

    $statement = $pdo->prepare(
        'UPDATE sandbox_bindings
        SET width_value = :width_value,
            width_unit = :width_unit,
            height_value = :height_value,
            height_unit = :height_unit,
            alignment = :alignment,
            fallback_value = :fallback_value,
            visibility_rule = :visibility_rule
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
        $version['style_json'] = decode_json_column($version['style_json'] ?? null);
        $version['data_contract_json'] = decode_json_column($version['data_contract_json'] ?? null);
        $version['preview_json'] = decode_json_column($version['preview_json'] ?? null);
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
    $model['selected_binding'] = null;

    foreach ($bindings as $binding) {
        if ($model['selected_binding_id'] !== null && (int) $binding['id'] === (int) $model['selected_binding_id']) {
            $model['selected_binding'] = $binding;
            $model['selected_slot'] = $binding['slot_key'];
            break;
        }

        if (($binding['slot_key'] ?? null) === $model['selected_slot'] && $model['selected_binding'] === null) {
            $model['selected_binding'] = $binding;
        }
    }

    return $model;
}
