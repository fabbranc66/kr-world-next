CREATE DATABASE IF NOT EXISTS kr_world
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE kr_world;

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS live_vote_entries;
DROP TABLE IF EXISTS live_request_entries;
DROP TABLE IF EXISTS live_sessions;
DROP TABLE IF EXISTS sandbox_bindings;
DROP TABLE IF EXISTS sandbox_model_versions;
DROP TABLE IF EXISTS sandbox_models;
DROP TABLE IF EXISTS menu_items;
DROP TABLE IF EXISTS menus;
DROP TABLE IF EXISTS content_relations;
DROP TABLE IF EXISTS content_tag_links;
DROP TABLE IF EXISTS taxonomy_terms;
DROP TABLE IF EXISTS taxonomies;
DROP TABLE IF EXISTS media_usages;
DROP TABLE IF EXISTS media_assets;
DROP TABLE IF EXISTS content_blocks;
DROP TABLE IF EXISTS module_definitions;
DROP TABLE IF EXISTS content_items;
DROP TABLE IF EXISTS skins;
DROP TABLE IF EXISTS templates;
DROP TABLE IF EXISTS content_families;
DROP TABLE IF EXISTS content_types;
DROP TABLE IF EXISTS data_source_fields;
DROP TABLE IF EXISTS data_sources;
DROP TABLE IF EXISTS settings;
DROP TABLE IF EXISTS admin_user_roles;
DROP TABLE IF EXISTS admin_roles;
DROP TABLE IF EXISTS admin_users;

SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE admin_users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(190) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    display_name VARCHAR(120) NOT NULL,
    status ENUM('active', 'disabled') NOT NULL DEFAULT 'active',
    last_login_at DATETIME NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_admin_users_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE admin_roles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    role_key VARCHAR(80) NOT NULL,
    label VARCHAR(120) NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_admin_roles_key (role_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE admin_user_roles (
    user_id BIGINT UNSIGNED NOT NULL,
    role_id BIGINT UNSIGNED NOT NULL,
    assigned_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id, role_id),
    CONSTRAINT fk_admin_user_roles_user
        FOREIGN KEY (user_id) REFERENCES admin_users(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_admin_user_roles_role
        FOREIGN KEY (role_id) REFERENCES admin_roles(id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE settings (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    scope ENUM('system', 'public', 'admin', 'sandbox', 'live_hub') NOT NULL DEFAULT 'system',
    setting_key VARCHAR(120) NOT NULL,
    setting_value JSON NULL,
    notes TEXT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_settings_scope_key (scope, setting_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE data_sources (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    source_key VARCHAR(120) NOT NULL,
    label VARCHAR(160) NOT NULL,
    source_type ENUM('table', 'view', 'query', 'service') NOT NULL DEFAULT 'table',
    table_name VARCHAR(160) NULL,
    config_json JSON NULL,
    is_bindable TINYINT(1) NOT NULL DEFAULT 1,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_data_sources_key (source_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE data_source_fields (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    data_source_id BIGINT UNSIGNED NOT NULL,
    field_key VARCHAR(120) NOT NULL,
    label VARCHAR(160) NOT NULL,
    field_type ENUM('string', 'text', 'html', 'number', 'boolean', 'date', 'datetime', 'json', 'media', 'relation', 'list') NOT NULL DEFAULT 'string',
    relation_source_key VARCHAR(120) NULL,
    is_required TINYINT(1) NOT NULL DEFAULT 0,
    is_filterable TINYINT(1) NOT NULL DEFAULT 0,
    is_bindable TINYINT(1) NOT NULL DEFAULT 1,
    fallback_value TEXT NULL,
    config_json JSON NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_data_source_fields_key (data_source_id, field_key),
    KEY idx_data_source_fields_relation (relation_source_key),
    CONSTRAINT fk_data_source_fields_source
        FOREIGN KEY (data_source_id) REFERENCES data_sources(id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE content_types (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    type_key VARCHAR(80) NOT NULL,
    label VARCHAR(120) NOT NULL,
    route_pattern VARCHAR(190) NOT NULL,
    description TEXT NULL,
    is_public TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_content_types_key (type_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE content_families (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    family_key VARCHAR(80) NOT NULL,
    label VARCHAR(120) NOT NULL,
    description TEXT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_content_families_key (family_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE templates (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    template_key VARCHAR(120) NOT NULL,
    name VARCHAR(160) NOT NULL,
    status ENUM('draft', 'validated', 'ready') NOT NULL DEFAULT 'draft',
    structure_json JSON NOT NULL,
    slot_schema_json JSON NULL,
    css_config_json JSON NULL,
    notes TEXT NULL,
    created_by BIGINT UNSIGNED NULL,
    validated_by BIGINT UNSIGNED NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_templates_key (template_key),
    KEY idx_templates_status (status),
    CONSTRAINT fk_templates_created_by
        FOREIGN KEY (created_by) REFERENCES admin_users(id)
        ON DELETE SET NULL,
    CONSTRAINT fk_templates_validated_by
        FOREIGN KEY (validated_by) REFERENCES admin_users(id)
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE skins (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    skin_key VARCHAR(120) NOT NULL,
    name VARCHAR(160) NOT NULL,
    status ENUM('draft', 'validated', 'ready') NOT NULL DEFAULT 'draft',
    token_json JSON NOT NULL,
    typography_json JSON NULL,
    css_config_json JSON NULL,
    notes TEXT NULL,
    created_by BIGINT UNSIGNED NULL,
    validated_by BIGINT UNSIGNED NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_skins_key (skin_key),
    KEY idx_skins_status (status),
    CONSTRAINT fk_skins_created_by
        FOREIGN KEY (created_by) REFERENCES admin_users(id)
        ON DELETE SET NULL,
    CONSTRAINT fk_skins_validated_by
        FOREIGN KEY (validated_by) REFERENCES admin_users(id)
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE content_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    content_type_id BIGINT UNSIGNED NOT NULL,
    family_id BIGINT UNSIGNED NULL,
    template_id BIGINT UNSIGNED NULL,
    skin_id BIGINT UNSIGNED NULL,
    parent_id BIGINT UNSIGNED NULL,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(190) NOT NULL,
    route_mode ENUM('kr_world', 'page_slug', 'event_slug', 'custom') NOT NULL DEFAULT 'page_slug',
    custom_path VARCHAR(255) NULL,
    status ENUM('draft', 'review', 'published', 'archived') NOT NULL DEFAULT 'draft',
    visibility ENUM('public', 'private', 'hidden', 'live_hub') NOT NULL DEFAULT 'public',
    hero_title VARCHAR(255) NULL,
    hero_subtitle VARCHAR(255) NULL,
    summary TEXT NULL,
    content_json JSON NULL,
    seo_json JSON NULL,
    query_focus VARCHAR(255) NULL,
    sort_order INT NOT NULL DEFAULT 0,
    published_at DATETIME NULL,
    created_by BIGINT UNSIGNED NULL,
    updated_by BIGINT UNSIGNED NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_content_items_slug_type (content_type_id, slug),
    KEY idx_content_items_status_visibility (status, visibility),
    KEY idx_content_items_family (family_id),
    KEY idx_content_items_parent (parent_id),
    KEY idx_content_items_template (template_id),
    KEY idx_content_items_skin (skin_id),
    CONSTRAINT fk_content_items_type
        FOREIGN KEY (content_type_id) REFERENCES content_types(id),
    CONSTRAINT fk_content_items_family
        FOREIGN KEY (family_id) REFERENCES content_families(id)
        ON DELETE SET NULL,
    CONSTRAINT fk_content_items_template
        FOREIGN KEY (template_id) REFERENCES templates(id)
        ON DELETE SET NULL,
    CONSTRAINT fk_content_items_skin
        FOREIGN KEY (skin_id) REFERENCES skins(id)
        ON DELETE SET NULL,
    CONSTRAINT fk_content_items_parent
        FOREIGN KEY (parent_id) REFERENCES content_items(id)
        ON DELETE SET NULL,
    CONSTRAINT fk_content_items_created_by
        FOREIGN KEY (created_by) REFERENCES admin_users(id)
        ON DELETE SET NULL,
    CONSTRAINT fk_content_items_updated_by
        FOREIGN KEY (updated_by) REFERENCES admin_users(id)
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE module_definitions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    module_key VARCHAR(120) NOT NULL,
    label VARCHAR(160) NOT NULL,
    domain_scope ENUM('base', 'content', 'chart', 'event', 'recap', 'navigation', 'live_hub', 'utility') NOT NULL DEFAULT 'base',
    module_kind ENUM('header', 'content', 'media', 'relation', 'list', 'cta', 'faq', 'navigation', 'data', 'decorative', 'live') NOT NULL DEFAULT 'content',
    config_schema_json JSON NULL,
    data_contract_json JSON NULL,
    default_style_json JSON NULL,
    is_reusable TINYINT(1) NOT NULL DEFAULT 1,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_module_definitions_key (module_key),
    KEY idx_module_definitions_scope_kind (domain_scope, module_kind)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE content_blocks (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    content_item_id BIGINT UNSIGNED NOT NULL,
    parent_block_id BIGINT UNSIGNED NULL,
    module_definition_id BIGINT UNSIGNED NULL,
    slot_key VARCHAR(120) NOT NULL,
    block_key VARCHAR(120) NULL,
    label VARCHAR(160) NULL,
    sort_order INT NOT NULL DEFAULT 0,
    visibility ENUM('default', 'hidden', 'conditional') NOT NULL DEFAULT 'default',
    content_mode ENUM('static', 'bound', 'hybrid') NOT NULL DEFAULT 'static',
    layout_json JSON NULL,
    style_json JSON NULL,
    content_json JSON NULL,
    data_contract_json JSON NULL,
    binding_json JSON NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY idx_content_blocks_content_slot (content_item_id, slot_key, sort_order),
    KEY idx_content_blocks_parent (parent_block_id),
    CONSTRAINT fk_content_blocks_item
        FOREIGN KEY (content_item_id) REFERENCES content_items(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_content_blocks_parent
        FOREIGN KEY (parent_block_id) REFERENCES content_blocks(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_content_blocks_module
        FOREIGN KEY (module_definition_id) REFERENCES module_definitions(id)
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE media_assets (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    asset_type ENUM('image', 'video', 'audio', 'document', 'embed') NOT NULL DEFAULT 'image',
    storage_disk ENUM('local', 'aruba', 'external') NOT NULL DEFAULT 'local',
    file_path VARCHAR(255) NULL,
    file_name VARCHAR(255) NULL,
    mime_type VARCHAR(120) NULL,
    alt_text VARCHAR(255) NULL,
    caption TEXT NULL,
    credit VARCHAR(255) NULL,
    width INT NULL,
    height INT NULL,
    ratio VARCHAR(40) NULL,
    meta_json JSON NULL,
    created_by BIGINT UNSIGNED NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY idx_media_assets_type (asset_type),
    CONSTRAINT fk_media_assets_created_by
        FOREIGN KEY (created_by) REFERENCES admin_users(id)
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE media_usages (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    media_asset_id BIGINT UNSIGNED NOT NULL,
    content_item_id BIGINT UNSIGNED NULL,
    content_block_id BIGINT UNSIGNED NULL,
    usage_scope ENUM('content', 'block', 'template', 'skin', 'sandbox') NOT NULL DEFAULT 'content',
    role_key VARCHAR(120) NOT NULL,
    crop_x DECIMAL(8,4) NULL,
    crop_y DECIMAL(8,4) NULL,
    crop_scale DECIMAL(8,4) NULL,
    focus_x DECIMAL(8,4) NULL,
    focus_y DECIMAL(8,4) NULL,
    fit_mode ENUM('cover', 'contain', 'fill') NULL,
    override_caption TEXT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    KEY idx_media_usages_scope_role (usage_scope, role_key),
    CONSTRAINT fk_media_usages_asset
        FOREIGN KEY (media_asset_id) REFERENCES media_assets(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_media_usages_content
        FOREIGN KEY (content_item_id) REFERENCES content_items(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_media_usages_block
        FOREIGN KEY (content_block_id) REFERENCES content_blocks(id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE taxonomies (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    taxonomy_key VARCHAR(80) NOT NULL,
    label VARCHAR(120) NOT NULL,
    taxonomy_type ENUM('tag', 'category', 'cluster', 'vocabulary') NOT NULL DEFAULT 'tag',
    is_controlled TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_taxonomies_key (taxonomy_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE taxonomy_terms (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    taxonomy_id BIGINT UNSIGNED NOT NULL,
    parent_id BIGINT UNSIGNED NULL,
    term_key VARCHAR(120) NOT NULL,
    label VARCHAR(160) NOT NULL,
    slug VARCHAR(190) NOT NULL,
    description TEXT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_taxonomy_terms_key (taxonomy_id, term_key),
    UNIQUE KEY uq_taxonomy_terms_slug (taxonomy_id, slug),
    KEY idx_taxonomy_terms_parent (parent_id),
    CONSTRAINT fk_taxonomy_terms_taxonomy
        FOREIGN KEY (taxonomy_id) REFERENCES taxonomies(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_taxonomy_terms_parent
        FOREIGN KEY (parent_id) REFERENCES taxonomy_terms(id)
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE content_tag_links (
    content_item_id BIGINT UNSIGNED NOT NULL,
    taxonomy_term_id BIGINT UNSIGNED NOT NULL,
    linked_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (content_item_id, taxonomy_term_id),
    CONSTRAINT fk_content_tag_links_content
        FOREIGN KEY (content_item_id) REFERENCES content_items(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_content_tag_links_term
        FOREIGN KEY (taxonomy_term_id) REFERENCES taxonomy_terms(id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE content_relations (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    source_content_id BIGINT UNSIGNED NOT NULL,
    target_content_id BIGINT UNSIGNED NOT NULL,
    relation_type VARCHAR(80) NOT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    meta_json JSON NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_content_relations_pair (source_content_id, target_content_id, relation_type),
    KEY idx_content_relations_target (target_content_id, relation_type),
    CONSTRAINT fk_content_relations_source
        FOREIGN KEY (source_content_id) REFERENCES content_items(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_content_relations_target
        FOREIGN KEY (target_content_id) REFERENCES content_items(id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE menus (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    menu_key VARCHAR(120) NOT NULL,
    label VARCHAR(160) NOT NULL,
    menu_scope ENUM('global', 'section', 'page', 'relational', 'functional', 'domain', 'admin', 'sandbox') NOT NULL DEFAULT 'global',
    description TEXT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_menus_key (menu_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE menu_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    menu_id BIGINT UNSIGNED NOT NULL,
    parent_id BIGINT UNSIGNED NULL,
    content_item_id BIGINT UNSIGNED NULL,
    label VARCHAR(160) NOT NULL,
    url VARCHAR(255) NULL,
    target ENUM('_self', '_blank') NOT NULL DEFAULT '_self',
    item_kind ENUM('content', 'custom', 'anchor', 'action') NOT NULL DEFAULT 'content',
    sort_order INT NOT NULL DEFAULT 0,
    meta_json JSON NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY idx_menu_items_menu_sort (menu_id, sort_order),
    KEY idx_menu_items_parent (parent_id),
    CONSTRAINT fk_menu_items_menu
        FOREIGN KEY (menu_id) REFERENCES menus(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_menu_items_parent
        FOREIGN KEY (parent_id) REFERENCES menu_items(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_menu_items_content
        FOREIGN KEY (content_item_id) REFERENCES content_items(id)
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE sandbox_models (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    model_type ENUM('section', 'template', 'skin', 'configuration') NOT NULL,
    model_key VARCHAR(120) NOT NULL,
    name VARCHAR(160) NOT NULL,
    status ENUM('draft', 'validated', 'ready') NOT NULL DEFAULT 'draft',
    linked_template_id BIGINT UNSIGNED NULL,
    linked_skin_id BIGINT UNSIGNED NULL,
    preview_content_type_id BIGINT UNSIGNED NULL,
    preview_data_source_id BIGINT UNSIGNED NULL,
    notes TEXT NULL,
    created_by BIGINT UNSIGNED NULL,
    updated_by BIGINT UNSIGNED NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_sandbox_models_key (model_type, model_key),
    KEY idx_sandbox_models_status (status),
    CONSTRAINT fk_sandbox_models_template
        FOREIGN KEY (linked_template_id) REFERENCES templates(id)
        ON DELETE SET NULL,
    CONSTRAINT fk_sandbox_models_skin
        FOREIGN KEY (linked_skin_id) REFERENCES skins(id)
        ON DELETE SET NULL,
    CONSTRAINT fk_sandbox_models_preview_type
        FOREIGN KEY (preview_content_type_id) REFERENCES content_types(id)
        ON DELETE SET NULL,
    CONSTRAINT fk_sandbox_models_preview_source
        FOREIGN KEY (preview_data_source_id) REFERENCES data_sources(id)
        ON DELETE SET NULL,
    CONSTRAINT fk_sandbox_models_created_by
        FOREIGN KEY (created_by) REFERENCES admin_users(id)
        ON DELETE SET NULL,
    CONSTRAINT fk_sandbox_models_updated_by
        FOREIGN KEY (updated_by) REFERENCES admin_users(id)
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE sandbox_model_versions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    sandbox_model_id BIGINT UNSIGNED NOT NULL,
    version_no INT NOT NULL,
    structure_json JSON NOT NULL,
    style_json JSON NULL,
    data_contract_json JSON NULL,
    preview_json JSON NULL,
    change_notes TEXT NULL,
    created_by BIGINT UNSIGNED NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_sandbox_model_versions_no (sandbox_model_id, version_no),
    CONSTRAINT fk_sandbox_model_versions_model
        FOREIGN KEY (sandbox_model_id) REFERENCES sandbox_models(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_sandbox_model_versions_created_by
        FOREIGN KEY (created_by) REFERENCES admin_users(id)
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE sandbox_bindings (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    sandbox_model_version_id BIGINT UNSIGNED NOT NULL,
    data_source_id BIGINT UNSIGNED NOT NULL,
    field_key VARCHAR(120) NOT NULL,
    field_alias VARCHAR(160) NULL,
    bind_type ENUM('single', 'relation', 'collection', 'contextual', 'media') NOT NULL DEFAULT 'single',
    slot_key VARCHAR(120) NOT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    position_x DECIMAL(8,2) NULL,
    position_y DECIMAL(8,2) NULL,
    width_value DECIMAL(8,2) NULL,
    width_unit ENUM('px', 'percent') NULL,
    height_value DECIMAL(8,2) NULL,
    height_unit ENUM('px', 'percent') NULL,
    alignment ENUM('start', 'center', 'end', 'stretch') NULL,
    visibility_rule JSON NULL,
    fallback_value TEXT NULL,
    media_config_json JSON NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    KEY idx_sandbox_bindings_slot (sandbox_model_version_id, slot_key, sort_order),
    CONSTRAINT fk_sandbox_bindings_version
        FOREIGN KEY (sandbox_model_version_id) REFERENCES sandbox_model_versions(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_sandbox_bindings_source
        FOREIGN KEY (data_source_id) REFERENCES data_sources(id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE live_sessions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    session_key VARCHAR(120) NOT NULL,
    module_type ENUM('karaoke', 'dance', 'vote', 'game', 'screen') NOT NULL,
    title VARCHAR(190) NOT NULL,
    status ENUM('draft', 'scheduled', 'live', 'paused', 'closed') NOT NULL DEFAULT 'draft',
    access_mode ENUM('hidden', 'qr_only', 'operator', 'mixed') NOT NULL DEFAULT 'hidden',
    starts_at DATETIME NULL,
    ends_at DATETIME NULL,
    config_json JSON NULL,
    created_by BIGINT UNSIGNED NULL,
    updated_by BIGINT UNSIGNED NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_live_sessions_key (session_key),
    KEY idx_live_sessions_module_status (module_type, status),
    CONSTRAINT fk_live_sessions_created_by
        FOREIGN KEY (created_by) REFERENCES admin_users(id)
        ON DELETE SET NULL,
    CONSTRAINT fk_live_sessions_updated_by
        FOREIGN KEY (updated_by) REFERENCES admin_users(id)
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE live_request_entries (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    live_session_id BIGINT UNSIGNED NOT NULL,
    request_type ENUM('karaoke_song', 'dance_song', 'generic') NOT NULL DEFAULT 'generic',
    requester_name VARCHAR(160) NULL,
    requester_contact VARCHAR(190) NULL,
    request_label VARCHAR(255) NOT NULL,
    request_payload JSON NULL,
    status ENUM('pending', 'approved', 'queued', 'served', 'rejected', 'cancelled') NOT NULL DEFAULT 'pending',
    queue_position INT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY idx_live_request_entries_status (live_session_id, status, queue_position),
    CONSTRAINT fk_live_request_entries_session
        FOREIGN KEY (live_session_id) REFERENCES live_sessions(id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE live_vote_entries (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    live_session_id BIGINT UNSIGNED NOT NULL,
    voter_token VARCHAR(190) NOT NULL,
    subject_key VARCHAR(190) NOT NULL,
    score DECIMAL(8,2) NULL,
    payload_json JSON NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    KEY idx_live_vote_entries_subject (live_session_id, subject_key),
    CONSTRAINT fk_live_vote_entries_session
        FOREIGN KEY (live_session_id) REFERENCES live_sessions(id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO admin_roles (role_key, label) VALUES
('super_admin', 'Super Admin'),
('editor', 'Editor'),
('designer', 'Designer'),
('live_operator', 'Live Operator');

INSERT INTO settings (scope, setting_key, setting_value, notes) VALUES
('system', 'brand_name', JSON_OBJECT('value', 'KR World'), 'Brand principale del progetto'),
('public', 'header_menu_key', JSON_OBJECT('value', 'main_header'), 'Menu principale header pubblico');

INSERT INTO content_types (type_key, label, route_pattern, description, is_public) VALUES
('hub', 'Home Hub', '/kr-world', 'Ingresso principale del sito pubblico', 1),
('page', 'Pagina', '/page?slug={slug}', 'Renderer dinamico per pagine editoriali e generiche', 1),
('event', 'Evento', '/event?slug={slug}', 'Evento singolo', 1),
('chart', 'Chart', '/page?slug={slug}', 'Contenuto chart renderizzato come pagina dinamica', 1),
('recap', 'Recap', '/page?slug={slug}', 'Recap editoriale o post-evento', 1),
('live_hub', 'Live Hub', '/live/{slug}', 'Contenuto non pubblico o operativo live', 0);

INSERT INTO content_families (family_key, label, description) VALUES
('editorial', 'Editoriale', 'Pagine e contenuti editoriali generici'),
('chart', 'Chart', 'Contenuti e dataset del dominio chart'),
('events', 'Eventi', 'Eventi singoli e raccolte correlate'),
('recaps', 'Recap', 'Recap serate, momenti chiave e riepiloghi'),
('live', 'Live Hub', 'Contenuti e interfacce del live hub');

INSERT INTO templates (template_key, name, status, structure_json, slot_schema_json, css_config_json, notes, created_by, validated_by) VALUES
(
    'page_base',
    'Page Base',
    'ready',
    JSON_OBJECT(
        'frame', JSON_ARRAY('header', 'hero', 'main', 'aside', 'related', 'faq', 'footer')
    ),
    JSON_OBJECT(
        'slots', JSON_ARRAY('hero', 'main', 'aside', 'related', 'faq')
    ),
    JSON_OBJECT(
        'layout', 'editorial_modular'
    ),
    'Template base per pagine modulari KR World',
    NULL,
    NULL
);

INSERT INTO skins (skin_key, name, status, token_json, typography_json, css_config_json, notes, created_by, validated_by) VALUES
(
    'editorial_dark',
    'Editorial Dark',
    'ready',
    JSON_OBJECT(
        'background', '#111111',
        'surface', '#1b1b1b',
        'text', '#f5f1e8',
        'accent', '#c14d2d',
        'border', '#353535'
    ),
    JSON_OBJECT(
        'title_font', 'Georgia',
        'body_font', 'Georgia',
        'scale', 'editorial'
    ),
    JSON_OBJECT(
        'header_mode', 'dark_sticky_compact'
    ),
    'Skin base iniziale coerente con direzione editoriale-musicale',
    NULL,
    NULL
);

INSERT INTO taxonomies (taxonomy_key, label, taxonomy_type, is_controlled) VALUES
('tags', 'Tag', 'tag', 1),
('clusters', 'Cluster', 'cluster', 1),
('topics', 'Topics', 'vocabulary', 1);

INSERT INTO data_sources (source_key, label, source_type, table_name, config_json, is_bindable, is_active) VALUES
('content_items', 'Contenuti KR World', 'table', 'content_items', JSON_OBJECT('mode', 'single_or_list'), 1, 1),
('content_blocks', 'Blocchi contenuto', 'table', 'content_blocks', JSON_OBJECT('mode', 'list'), 1, 1),
('media_assets', 'Media Library', 'table', 'media_assets', JSON_OBJECT('mode', 'single_or_list'), 1, 1),
('taxonomy_terms', 'Tassonomie', 'table', 'taxonomy_terms', JSON_OBJECT('mode', 'list'), 1, 1),
('live_sessions', 'Sessioni Live', 'table', 'live_sessions', JSON_OBJECT('mode', 'single_or_list'), 1, 1),
('live_request_entries', 'Richieste Live', 'table', 'live_request_entries', JSON_OBJECT('mode', 'list'), 1, 1);

INSERT INTO data_source_fields (data_source_id, field_key, label, field_type, relation_source_key, is_required, is_filterable, is_bindable, fallback_value, config_json)
SELECT id, 'title', 'Titolo', 'string', NULL, 1, 1, 1, NULL, JSON_OBJECT('input', 'text')
FROM data_sources WHERE source_key = 'content_items';

INSERT INTO data_source_fields (data_source_id, field_key, label, field_type, relation_source_key, is_required, is_filterable, is_bindable, fallback_value, config_json)
SELECT id, 'slug', 'Slug', 'string', NULL, 1, 1, 1, NULL, JSON_OBJECT('input', 'text')
FROM data_sources WHERE source_key = 'content_items';

INSERT INTO data_source_fields (data_source_id, field_key, label, field_type, relation_source_key, is_required, is_filterable, is_bindable, fallback_value, config_json)
SELECT id, 'summary', 'Summary', 'text', NULL, 0, 0, 1, '', JSON_OBJECT('input', 'textarea')
FROM data_sources WHERE source_key = 'content_items';

INSERT INTO data_source_fields (data_source_id, field_key, label, field_type, relation_source_key, is_required, is_filterable, is_bindable, fallback_value, config_json)
SELECT id, 'published_at', 'Pubblicazione', 'datetime', NULL, 0, 1, 1, NULL, JSON_OBJECT('format', 'datetime')
FROM data_sources WHERE source_key = 'content_items';

INSERT INTO data_source_fields (data_source_id, field_key, label, field_type, relation_source_key, is_required, is_filterable, is_bindable, fallback_value, config_json)
SELECT id, 'file_path', 'File Media', 'media', NULL, 0, 0, 1, NULL, JSON_OBJECT('accept', 'image,video')
FROM data_sources WHERE source_key = 'media_assets';

INSERT INTO data_source_fields (data_source_id, field_key, label, field_type, relation_source_key, is_required, is_filterable, is_bindable, fallback_value, config_json)
SELECT id, 'caption', 'Caption', 'text', NULL, 0, 0, 1, NULL, JSON_OBJECT('input', 'textarea')
FROM data_sources WHERE source_key = 'media_assets';

INSERT INTO module_definitions (module_key, label, domain_scope, module_kind, config_schema_json, data_contract_json, default_style_json, is_reusable, is_active) VALUES
('hero_base', 'Hero Base', 'base', 'header', JSON_OBJECT('fields', JSON_ARRAY('title', 'subtitle')), JSON_OBJECT('source_mode', 'single'), JSON_OBJECT('slot', 'hero'), 1, 1),
('text_block', 'Text Block', 'base', 'content', JSON_OBJECT('fields', JSON_ARRAY('body')), JSON_OBJECT('source_mode', 'static_or_bound'), JSON_OBJECT('slot', 'main'), 1, 1),
('media_single', 'Media Singolo', 'base', 'media', JSON_OBJECT('fields', JSON_ARRAY('asset', 'caption')), JSON_OBJECT('source_mode', 'single'), JSON_OBJECT('slot', 'main'), 1, 1),
('related_content', 'Contenuti Correlati', 'content', 'relation', JSON_OBJECT('fields', JSON_ARRAY('items')), JSON_OBJECT('source_mode', 'collection'), JSON_OBJECT('slot', 'main'), 1, 1),
('faq_list', 'FAQ', 'utility', 'faq', JSON_OBJECT('fields', JSON_ARRAY('items')), JSON_OBJECT('source_mode', 'static_or_bound'), JSON_OBJECT('slot', 'faq'), 1, 1),
('cta_block', 'CTA Block', 'utility', 'cta', JSON_OBJECT('fields', JSON_ARRAY('actions')), JSON_OBJECT('source_mode', 'static'), JSON_OBJECT('slot', 'aside'), 1, 1),
('chart_table', 'Tabella Chart', 'chart', 'data', JSON_OBJECT('fields', JSON_ARRAY('rows')), JSON_OBJECT('source_mode', 'collection'), JSON_OBJECT('slot', 'main'), 1, 1),
('event_meta', 'Metadati Evento', 'event', 'data', JSON_OBJECT('fields', JSON_ARRAY('date', 'venue', 'ingresso')), JSON_OBJECT('source_mode', 'single'), JSON_OBJECT('slot', 'hero'), 1, 1),
('recap_summary', 'Summary Recap', 'recap', 'content', JSON_OBJECT('fields', JSON_ARRAY('summary')), JSON_OBJECT('source_mode', 'single'), JSON_OBJECT('slot', 'main'), 1, 1),
('live_request_list', 'Lista Richieste Live', 'live_hub', 'live', JSON_OBJECT('fields', JSON_ARRAY('requests')), JSON_OBJECT('source_mode', 'collection'), JSON_OBJECT('slot', 'main'), 1, 1);

INSERT INTO content_items (
    content_type_id,
    family_id,
    template_id,
    skin_id,
    parent_id,
    title,
    slug,
    route_mode,
    custom_path,
    status,
    visibility,
    hero_title,
    hero_subtitle,
    summary,
    content_json,
    seo_json,
    query_focus,
    sort_order,
    published_at,
    created_by,
    updated_by
)
SELECT
    ct.id,
    cf.id,
    t.id,
    s.id,
    NULL,
    'KR World',
    'kr-world',
    'kr_world',
    '/kr-world',
    'published',
    'public',
    'KR World',
    'Home hub iniziale del progetto',
    'Punto di ingresso pubblico del nuovo KR World.',
    JSON_OBJECT('intro', 'Home base pronta per evolvere con moduli e admin.'),
    JSON_OBJECT('title', 'KR World', 'description', 'Home hub iniziale KR World'),
    'kr world',
    0,
    NOW(),
    NULL,
    NULL
FROM content_types ct
JOIN content_families cf ON cf.family_key = 'editorial'
JOIN templates t ON t.template_key = 'page_base'
JOIN skins s ON s.skin_key = 'editorial_dark'
WHERE ct.type_key = 'hub';

INSERT INTO content_items (
    content_type_id,
    family_id,
    template_id,
    skin_id,
    title,
    slug,
    route_mode,
    custom_path,
    status,
    visibility,
    hero_title,
    hero_subtitle,
    summary,
    content_json,
    seo_json,
    query_focus,
    sort_order,
    published_at
)
SELECT
    ct.id,
    cf.id,
    t.id,
    s.id,
    'Chart Hub',
    'chart-hub',
    'page_slug',
    NULL,
    'published',
    'public',
    'Chart Hub',
    'Pagina editoriale dedicata alle chart.',
    'Hub editoriale per contenuti chart.',
    JSON_OBJECT('intro', 'Pagina chart iniziale'),
    JSON_OBJECT('title', 'Chart Hub', 'description', 'Pagina hub dedicata alle chart di KR World'),
    'chart hub',
    10,
    NOW()
FROM content_types ct
JOIN content_families cf ON cf.family_key = 'chart'
JOIN templates t ON t.template_key = 'page_base'
JOIN skins s ON s.skin_key = 'editorial_dark'
WHERE ct.type_key = 'page';

INSERT INTO content_items (
    content_type_id,
    family_id,
    template_id,
    skin_id,
    title,
    slug,
    route_mode,
    custom_path,
    status,
    visibility,
    hero_title,
    hero_subtitle,
    summary,
    content_json,
    seo_json,
    query_focus,
    sort_order,
    published_at
)
SELECT
    ct.id,
    cf.id,
    t.id,
    s.id,
    'KR World Night',
    'kr-world-night',
    'event_slug',
    NULL,
    'published',
    'public',
    'KR World Night',
    'Evento singolo iniziale del progetto.',
    'Scheda evento iniziale.',
    JSON_OBJECT('intro', 'Evento seed iniziale'),
    JSON_OBJECT('title', 'KR World Night', 'description', 'Evento live iniziale KR World'),
    'evento kr world',
    20,
    NOW()
FROM content_types ct
JOIN content_families cf ON cf.family_key = 'events'
JOIN templates t ON t.template_key = 'page_base'
JOIN skins s ON s.skin_key = 'editorial_dark'
WHERE ct.type_key = 'event';

INSERT INTO content_blocks (
    content_item_id,
    parent_block_id,
    module_definition_id,
    slot_key,
    block_key,
    label,
    sort_order,
    visibility,
    content_mode,
    layout_json,
    style_json,
    content_json,
    data_contract_json,
    binding_json
)
SELECT
    ci.id,
    NULL,
    md.id,
    'hero',
    'home_hero',
    'Hero Home',
    10,
    'default',
    'static',
    JSON_OBJECT('width', 'full', 'align', 'start'),
    JSON_OBJECT('variant', 'hero_base'),
    JSON_OBJECT('title', 'KR World', 'subtitle', 'Base minima raggiungibile e struttura pronta per crescere.'),
    JSON_OBJECT('source_mode', 'static'),
    NULL
FROM content_items ci
JOIN module_definitions md ON md.module_key = 'hero_base'
WHERE ci.slug = 'kr-world';

INSERT INTO content_blocks (
    content_item_id,
    parent_block_id,
    module_definition_id,
    slot_key,
    block_key,
    label,
    sort_order,
    visibility,
    content_mode,
    layout_json,
    style_json,
    content_json,
    data_contract_json,
    binding_json
)
SELECT
    ci.id,
    NULL,
    md.id,
    'main',
    'home_intro',
    'Intro Home',
    20,
    'default',
    'static',
    JSON_OBJECT('width', 'boxed', 'align', 'start'),
    JSON_OBJECT('variant', 'intro_text'),
    JSON_OBJECT('body', 'Base tecnica coerente con Aruba Hosting, MySQL Aruba, crescita da admin e sviluppo futuro di sandbox e design lab.'),
    JSON_OBJECT('source_mode', 'static'),
    NULL
FROM content_items ci
JOIN module_definitions md ON md.module_key = 'text_block'
WHERE ci.slug = 'kr-world';

INSERT INTO content_blocks (
    content_item_id,
    parent_block_id,
    module_definition_id,
    slot_key,
    block_key,
    label,
    sort_order,
    visibility,
    content_mode,
    layout_json,
    style_json,
    content_json,
    data_contract_json,
    binding_json
)
SELECT
    ci.id,
    NULL,
    md.id,
    'related',
    'home_related',
    'Correlati Home',
    30,
    'default',
    'bound',
    JSON_OBJECT('width', 'boxed'),
    JSON_OBJECT('variant', 'related_cards'),
    JSON_OBJECT('title', 'Percorsi correlati'),
    JSON_OBJECT('source_mode', 'relation', 'relation_type', 'related', 'record_mode', 'collection'),
    JSON_OBJECT('relation_type', 'related', 'limit', 3)
FROM content_items ci
JOIN module_definitions md ON md.module_key = 'related_content'
WHERE ci.slug = 'kr-world';

INSERT INTO content_blocks (
    content_item_id,
    parent_block_id,
    module_definition_id,
    slot_key,
    block_key,
    label,
    sort_order,
    visibility,
    content_mode,
    layout_json,
    style_json,
    content_json,
    data_contract_json,
    binding_json
)
SELECT
    ci.id,
    NULL,
    md.id,
    'faq',
    'home_faq',
    'FAQ Home',
    40,
    'default',
    'static',
    JSON_OBJECT('width', 'boxed'),
    JSON_OBJECT('variant', 'faq_stack'),
    JSON_OBJECT(
        'title', 'FAQ',
        'items', JSON_ARRAY(
            JSON_OBJECT('question', 'KR World nasce gia'' orientato SEO?', 'answer', 'Si, con URL canonici, contenuti amministrabili e moduli dati-driven.'),
            JSON_OBJECT('question', 'La sandbox arriva dopo questa base?', 'answer', 'Si, dopo la chiusura del primo micro-strato dati e moduli riusabili.')
        )
    ),
    JSON_OBJECT('source_mode', 'static'),
    NULL
FROM content_items ci
JOIN module_definitions md ON md.module_key = 'faq_list'
WHERE ci.slug = 'kr-world';

INSERT INTO content_blocks (
    content_item_id,
    parent_block_id,
    module_definition_id,
    slot_key,
    block_key,
    label,
    sort_order,
    visibility,
    content_mode,
    layout_json,
    style_json,
    content_json,
    data_contract_json,
    binding_json
)
SELECT
    ci.id,
    NULL,
    md.id,
    'aside',
    'home_cta',
    'CTA Home',
    50,
    'default',
    'static',
    JSON_OBJECT('width', 'boxed'),
    JSON_OBJECT('variant', 'cta_cluster'),
    JSON_OBJECT(
        'title', 'Esplora',
        'body', 'Accedi ai primi nuclei contenutistici del progetto.',
        'actions', JSON_ARRAY(
            JSON_OBJECT('label', 'Apri Chart', 'url', '/page?slug=chart-hub'),
            JSON_OBJECT('label', 'Apri Evento', 'url', '/event?slug=kr-world-night')
        )
    ),
    JSON_OBJECT('source_mode', 'static'),
    NULL
FROM content_items ci
JOIN module_definitions md ON md.module_key = 'cta_block'
WHERE ci.slug = 'kr-world';

INSERT INTO content_blocks (
    content_item_id,
    parent_block_id,
    module_definition_id,
    slot_key,
    block_key,
    label,
    sort_order,
    visibility,
    content_mode,
    layout_json,
    style_json,
    content_json,
    data_contract_json,
    binding_json
)
SELECT
    ci.id,
    NULL,
    md.id,
    'hero',
    'chart_hub_hero',
    'Hero Chart Hub',
    10,
    'default',
    'static',
    JSON_OBJECT('width', 'full', 'align', 'start'),
    JSON_OBJECT('variant', 'hero_base'),
    JSON_OBJECT('title', 'Chart Hub', 'subtitle', 'Contenitore editoriale per chart, ranking e approfondimenti.'),
    JSON_OBJECT('source_mode', 'static'),
    NULL
FROM content_items ci
JOIN module_definitions md ON md.module_key = 'hero_base'
WHERE ci.slug = 'chart-hub';

INSERT INTO content_blocks (
    content_item_id,
    parent_block_id,
    module_definition_id,
    slot_key,
    block_key,
    label,
    sort_order,
    visibility,
    content_mode,
    layout_json,
    style_json,
    content_json,
    data_contract_json,
    binding_json
)
SELECT
    ci.id,
    NULL,
    md.id,
    'main',
    'chart_hub_intro',
    'Intro Chart Hub',
    20,
    'default',
    'static',
    JSON_OBJECT('width', 'boxed', 'align', 'start'),
    JSON_OBJECT('variant', 'intro_text'),
    JSON_OBJECT('body', 'Pagina seed iniziale per il dominio chart, pronta a essere sostituita da contenuti amministrati.'),
    JSON_OBJECT('source_mode', 'static'),
    NULL
FROM content_items ci
JOIN module_definitions md ON md.module_key = 'text_block'
WHERE ci.slug = 'chart-hub';

INSERT INTO content_blocks (
    content_item_id,
    parent_block_id,
    module_definition_id,
    slot_key,
    block_key,
    label,
    sort_order,
    visibility,
    content_mode,
    layout_json,
    style_json,
    content_json,
    data_contract_json,
    binding_json
)
SELECT
    ci.id,
    NULL,
    md.id,
    'related',
    'chart_related',
    'Correlati Chart',
    30,
    'default',
    'bound',
    JSON_OBJECT('width', 'boxed'),
    JSON_OBJECT('variant', 'related_cards'),
    JSON_OBJECT('title', 'Contenuti correlati'),
    JSON_OBJECT('source_mode', 'relation', 'relation_type', 'related', 'record_mode', 'collection'),
    JSON_OBJECT('relation_type', 'related', 'limit', 3)
FROM content_items ci
JOIN module_definitions md ON md.module_key = 'related_content'
WHERE ci.slug = 'chart-hub';

INSERT INTO content_blocks (
    content_item_id,
    parent_block_id,
    module_definition_id,
    slot_key,
    block_key,
    label,
    sort_order,
    visibility,
    content_mode,
    layout_json,
    style_json,
    content_json,
    data_contract_json,
    binding_json
)
SELECT
    ci.id,
    NULL,
    md.id,
    'hero',
    'event_hero',
    'Hero Evento',
    10,
    'default',
    'static',
    JSON_OBJECT('width', 'full', 'align', 'start'),
    JSON_OBJECT('variant', 'hero_base'),
    JSON_OBJECT('title', 'KR World Night', 'subtitle', 'Evento singolo iniziale, compatibile con URL /event?slug=.'),
    JSON_OBJECT('source_mode', 'static'),
    NULL
FROM content_items ci
JOIN module_definitions md ON md.module_key = 'hero_base'
WHERE ci.slug = 'kr-world-night';

INSERT INTO content_blocks (
    content_item_id,
    parent_block_id,
    module_definition_id,
    slot_key,
    block_key,
    label,
    sort_order,
    visibility,
    content_mode,
    layout_json,
    style_json,
    content_json,
    data_contract_json,
    binding_json
)
SELECT
    ci.id,
    NULL,
    md.id,
    'main',
    'event_intro',
    'Intro Evento',
    20,
    'default',
    'static',
    JSON_OBJECT('width', 'boxed', 'align', 'start'),
    JSON_OBJECT('variant', 'intro_text'),
    JSON_OBJECT('body', 'Scheda evento seed iniziale per il renderer dinamico eventi.'),
    JSON_OBJECT('source_mode', 'static'),
    NULL
FROM content_items ci
JOIN module_definitions md ON md.module_key = 'text_block'
WHERE ci.slug = 'kr-world-night';

INSERT INTO content_blocks (
    content_item_id,
    parent_block_id,
    module_definition_id,
    slot_key,
    block_key,
    label,
    sort_order,
    visibility,
    content_mode,
    layout_json,
    style_json,
    content_json,
    data_contract_json,
    binding_json
)
SELECT
    ci.id,
    NULL,
    md.id,
    'aside',
    'event_cta',
    'CTA Evento',
    30,
    'default',
    'static',
    JSON_OBJECT('width', 'boxed'),
    JSON_OBJECT('variant', 'cta_cluster'),
    JSON_OBJECT(
        'title', 'Vai oltre',
        'body', 'Usa l''evento come ingresso verso altri nuclei del progetto.',
        'actions', JSON_ARRAY(
            JSON_OBJECT('label', 'Torna alla Home', 'url', '/kr-world'),
            JSON_OBJECT('label', 'Vai alle Chart', 'url', '/page?slug=chart-hub')
        )
    ),
    JSON_OBJECT('source_mode', 'static'),
    NULL
FROM content_items ci
JOIN module_definitions md ON md.module_key = 'cta_block'
WHERE ci.slug = 'kr-world-night';

INSERT INTO content_relations (source_content_id, target_content_id, relation_type, sort_order, meta_json)
SELECT src.id, dst.id, 'related', 10, JSON_OBJECT('context', 'home_to_chart')
FROM content_items src
JOIN content_items dst ON dst.slug = 'chart-hub'
WHERE src.slug = 'kr-world';

INSERT INTO content_relations (source_content_id, target_content_id, relation_type, sort_order, meta_json)
SELECT src.id, dst.id, 'related', 20, JSON_OBJECT('context', 'home_to_event')
FROM content_items src
JOIN content_items dst ON dst.slug = 'kr-world-night'
WHERE src.slug = 'kr-world';

INSERT INTO content_relations (source_content_id, target_content_id, relation_type, sort_order, meta_json)
SELECT src.id, dst.id, 'related', 10, JSON_OBJECT('context', 'chart_to_home')
FROM content_items src
JOIN content_items dst ON dst.slug = 'kr-world'
WHERE src.slug = 'chart-hub';

INSERT INTO content_relations (source_content_id, target_content_id, relation_type, sort_order, meta_json)
SELECT src.id, dst.id, 'related', 20, JSON_OBJECT('context', 'chart_to_event')
FROM content_items src
JOIN content_items dst ON dst.slug = 'kr-world-night'
WHERE src.slug = 'chart-hub';

INSERT INTO content_relations (source_content_id, target_content_id, relation_type, sort_order, meta_json)
SELECT src.id, dst.id, 'related', 10, JSON_OBJECT('context', 'event_to_home')
FROM content_items src
JOIN content_items dst ON dst.slug = 'kr-world'
WHERE src.slug = 'kr-world-night';

INSERT INTO content_relations (source_content_id, target_content_id, relation_type, sort_order, meta_json)
SELECT src.id, dst.id, 'related', 20, JSON_OBJECT('context', 'event_to_chart')
FROM content_items src
JOIN content_items dst ON dst.slug = 'chart-hub'
WHERE src.slug = 'kr-world-night';

INSERT INTO menus (menu_key, label, menu_scope, description, is_active) VALUES
('main_header', 'Main Header', 'global', 'Navigazione principale pubblica', 1);

INSERT INTO menu_items (menu_id, parent_id, content_item_id, label, url, target, item_kind, sort_order, meta_json, is_active)
SELECT
    m.id,
    NULL,
    ci.id,
    'Home',
    NULL,
    '_self',
    'content',
    10,
    JSON_OBJECT(),
    1
FROM menus m
JOIN content_items ci ON ci.slug = 'kr-world'
WHERE m.menu_key = 'main_header';

INSERT INTO menu_items (menu_id, parent_id, content_item_id, label, url, target, item_kind, sort_order, meta_json, is_active)
SELECT
    m.id,
    NULL,
    ci.id,
    'Chart',
    NULL,
    '_self',
    'content',
    20,
    JSON_OBJECT(),
    1
FROM menus m
JOIN content_items ci ON ci.slug = 'chart-hub'
WHERE m.menu_key = 'main_header';

INSERT INTO menu_items (menu_id, parent_id, content_item_id, label, url, target, item_kind, sort_order, meta_json, is_active)
SELECT
    m.id,
    NULL,
    ci.id,
    'Eventi',
    NULL,
    '_self',
    'content',
    30,
    JSON_OBJECT(),
    1
FROM menus m
JOIN content_items ci ON ci.slug = 'kr-world-night'
WHERE m.menu_key = 'main_header';

INSERT INTO sandbox_models (
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
)
SELECT
    'section',
    'homepage_lab',
    'Homepage Lab',
    'draft',
    t.id,
    s.id,
    ct.id,
    ds.id,
    'Primo nucleo della sandbox/design lab separata dal pubblico.',
    NULL,
    NULL
FROM templates t
JOIN skins s ON s.skin_key = 'editorial_dark'
JOIN content_types ct ON ct.type_key = 'page'
JOIN data_sources ds ON ds.source_key = 'content_items'
WHERE t.template_key = 'page_base';

INSERT INTO sandbox_model_versions (
    sandbox_model_id,
    version_no,
    structure_json,
    style_json,
    data_contract_json,
    preview_json,
    change_notes,
    created_by
)
SELECT
    sm.id,
    1,
    JSON_OBJECT(
        'frame', JSON_ARRAY('hero', 'main', 'aside'),
        'grid_mode', 'snap_soft',
        'selected_slot', 'hero'
    ),
    JSON_OBJECT(
        'skin_level', 'editorial_dark',
        'grid', 'enabled'
    ),
    JSON_OBJECT(
        'rules', JSON_ARRAY(
            'record singolo della pagina corrente',
            'binding da content_items',
            'supporto campi testo e summary',
            'slot guidati da struttura e non layout libero'
        )
    ),
    JSON_OBJECT(
        'title', 'Homepage Lab',
        'subtitle', 'Area di progettazione separata, pensata per preview e binding dati reali.',
        'panels', JSON_ARRAY('sorgenti dati', 'composizione', 'proprieta'),
        'placeholders', JSON_ARRAY(
            JSON_OBJECT('label', 'Hero Title', 'hint', 'binding da content_items.title', 'slot', 'hero'),
            JSON_OBJECT('label', 'Hero Summary', 'hint', 'binding da content_items.summary', 'slot', 'main'),
            JSON_OBJECT('label', 'Related Links', 'hint', 'collection bindabile da relazioni', 'slot', 'aside')
        )
    ),
    'Versione iniziale del design lab',
    NULL
FROM sandbox_models sm
WHERE sm.model_key = 'homepage_lab';

INSERT INTO sandbox_bindings (
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
)
SELECT
    smv.id,
    ds.id,
    'title',
    'Hero Title',
    'single',
    'hero',
    10,
    0,
    0,
    100,
    'percent',
    NULL,
    NULL,
    'start',
    JSON_OBJECT(),
    '',
    JSON_OBJECT()
FROM sandbox_model_versions smv
JOIN sandbox_models sm ON sm.id = smv.sandbox_model_id
JOIN data_sources ds ON ds.source_key = 'content_items'
WHERE sm.model_key = 'homepage_lab'
  AND smv.version_no = 1;

INSERT INTO sandbox_models (
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
)
SELECT
    'template',
    'event_story_lab',
    'Event Story Lab',
    'draft',
    t.id,
    s.id,
    ct.id,
    ds.id,
    'Laboratorio iniziale per schede evento e storytelling modulare.',
    NULL,
    NULL
FROM templates t
JOIN skins s ON s.skin_key = 'editorial_dark'
JOIN content_types ct ON ct.type_key = 'event'
JOIN data_sources ds ON ds.source_key = 'content_items'
WHERE t.template_key = 'page_base';

INSERT INTO sandbox_model_versions (
    sandbox_model_id,
    version_no,
    structure_json,
    style_json,
    data_contract_json,
    preview_json,
    change_notes,
    created_by
)
SELECT
    sm.id,
    1,
    JSON_OBJECT(
        'frame', JSON_ARRAY('hero', 'main', 'aside', 'faq'),
        'grid_mode', 'snap_strong',
        'selected_slot', 'hero'
    ),
    JSON_OBJECT(
        'skin_level', 'editorial_dark',
        'grid', 'enabled',
        'focus', 'event_story'
    ),
    JSON_OBJECT(
        'rules', JSON_ARRAY(
            'record singolo evento',
            'binding da content_items e content_relations',
            'slot hero main aside faq',
            'moduli riusabili per CTA e FAQ'
        )
    ),
    JSON_OBJECT(
        'title', 'Event Story Lab',
        'subtitle', 'Template laboratorio per eventi con corpo editoriale, CTA e supporto FAQ.',
        'panels', JSON_ARRAY('modelli', 'binding', 'preview'),
        'placeholders', JSON_ARRAY(
            JSON_OBJECT('label', 'Event Title', 'hint', 'binding da content_items.title', 'slot', 'hero'),
            JSON_OBJECT('label', 'Event Summary', 'hint', 'binding da content_items.summary', 'slot', 'main'),
            JSON_OBJECT('label', 'CTA Cluster', 'hint', 'azioni contestuali verso altri nuclei', 'slot', 'aside'),
            JSON_OBJECT('label', 'FAQ Event', 'hint', 'stack di domande utili', 'slot', 'faq')
        )
    ),
    'Versione iniziale del lab evento',
    NULL
FROM sandbox_models sm
WHERE sm.model_key = 'event_story_lab';

INSERT INTO sandbox_bindings (
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
)
SELECT
    smv.id,
    ds.id,
    'title',
    'Event Title',
    'single',
    'hero',
    10,
    0,
    0,
    100,
    'percent',
    NULL,
    NULL,
    'start',
    JSON_OBJECT(),
    '',
    JSON_OBJECT()
FROM sandbox_model_versions smv
JOIN sandbox_models sm ON sm.id = smv.sandbox_model_id
JOIN data_sources ds ON ds.source_key = 'content_items'
WHERE sm.model_key = 'event_story_lab'
  AND smv.version_no = 1;

INSERT INTO sandbox_bindings (
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
)
SELECT
    smv.id,
    ds.id,
    'summary',
    'Event Summary',
    'single',
    'main',
    20,
    0,
    0,
    100,
    'percent',
    NULL,
    NULL,
    'start',
    JSON_OBJECT(),
    '',
    JSON_OBJECT()
FROM sandbox_model_versions smv
JOIN sandbox_models sm ON sm.id = smv.sandbox_model_id
JOIN data_sources ds ON ds.source_key = 'content_items'
WHERE sm.model_key = 'event_story_lab'
  AND smv.version_no = 1;

INSERT INTO sandbox_bindings (
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
)
SELECT
    smv.id,
    ds.id,
    'summary',
    'Hero Summary',
    'single',
    'main',
    20,
    0,
    0,
    100,
    'percent',
    NULL,
    NULL,
    'start',
    JSON_OBJECT(),
    '',
    JSON_OBJECT()
FROM sandbox_model_versions smv
JOIN sandbox_models sm ON sm.id = smv.sandbox_model_id
JOIN data_sources ds ON ds.source_key = 'content_items'
WHERE sm.model_key = 'homepage_lab'
  AND smv.version_no = 1;
