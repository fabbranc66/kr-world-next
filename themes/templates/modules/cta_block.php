<?php

declare(strict_types=1);

$content = $block['content_json'];
$actions = $content['actions'] ?? [];
$basePath = $app['request']['base_path'] ?: '';
?>
<?php if ($actions !== []): ?>
    <section class="cta-section section-shell">
        <article class="cta-card">
            <?php if (!empty($content['title'])): ?>
                <p class="section-kicker"><?= htmlspecialchars((string) $content['title'], ENT_QUOTES, 'UTF-8') ?></p>
            <?php endif; ?>
            <?php if (!empty($content['body'])): ?>
                <p class="cta-copy"><?= htmlspecialchars((string) $content['body'], ENT_QUOTES, 'UTF-8') ?></p>
            <?php endif; ?>
            <div class="cta-actions">
                <?php foreach ($actions as $action): ?>
                    <?php
                    $url = (string) ($action['url'] ?? '#');
                    $isExternal = preg_match('/^https?:\/\//i', $url) === 1;
                    $href = $isExternal ? $url : $basePath . $url;
                    ?>
                    <a class="cta-button" href="<?= htmlspecialchars($href, ENT_QUOTES, 'UTF-8') ?>">
                        <?= htmlspecialchars((string) ($action['label'] ?? 'Apri'), ENT_QUOTES, 'UTF-8') ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </article>
    </section>
<?php endif; ?>
