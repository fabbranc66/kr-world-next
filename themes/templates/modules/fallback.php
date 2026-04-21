<?php

declare(strict_types=1);

$content = $block['content_json'];
$label = $block['label'] ?? $block['module_key'] ?? 'block';
?>
<section class="text-section section-shell">
    <article class="text-card">
        <p class="fallback-label"><?= htmlspecialchars((string) $label, ENT_QUOTES, 'UTF-8') ?></p>
        <?php if (!empty($content)): ?>
            <pre class="fallback-json"><?= htmlspecialchars((string) json_encode($content, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8') ?></pre>
        <?php endif; ?>
    </article>
</section>
