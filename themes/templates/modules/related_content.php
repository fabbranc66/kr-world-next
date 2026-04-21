<?php

declare(strict_types=1);

$content = $block['content_json'];
$items = $block['resolved_items'] ?? [];
$basePath = $app['request']['base_path'] ?: '';
?>
<?php if ($items !== []): ?>
    <section class="related-section section-shell">
        <?php if (!empty($content['title'])): ?>
            <div class="section-heading">
                <p class="section-kicker"><?= htmlspecialchars((string) $content['title'], ENT_QUOTES, 'UTF-8') ?></p>
            </div>
        <?php endif; ?>
        <div class="related-grid">
            <?php foreach ($items as $item): ?>
                <article class="related-card">
                    <h2><?= htmlspecialchars((string) $item['title'], ENT_QUOTES, 'UTF-8') ?></h2>
                    <?php if (!empty($item['summary'])): ?>
                        <p><?= htmlspecialchars((string) $item['summary'], ENT_QUOTES, 'UTF-8') ?></p>
                    <?php endif; ?>
                    <a class="related-link" href="<?= htmlspecialchars($basePath . $item['canonical_path'], ENT_QUOTES, 'UTF-8') ?>">Apri</a>
                </article>
            <?php endforeach; ?>
        </div>
    </section>
<?php endif; ?>
