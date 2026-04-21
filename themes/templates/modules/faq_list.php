<?php

declare(strict_types=1);

$content = $block['content_json'];
$items = $content['items'] ?? [];
?>
<?php if ($items !== []): ?>
    <section class="faq-section section-shell">
        <?php if (!empty($content['title'])): ?>
            <div class="section-heading">
                <p class="section-kicker"><?= htmlspecialchars((string) $content['title'], ENT_QUOTES, 'UTF-8') ?></p>
            </div>
        <?php endif; ?>
        <div class="faq-list">
            <?php foreach ($items as $item): ?>
                <article class="faq-card">
                    <?php if (!empty($item['question'])): ?>
                        <h2><?= htmlspecialchars((string) $item['question'], ENT_QUOTES, 'UTF-8') ?></h2>
                    <?php endif; ?>
                    <?php if (!empty($item['answer'])): ?>
                        <p><?= htmlspecialchars((string) $item['answer'], ENT_QUOTES, 'UTF-8') ?></p>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        </div>
    </section>
<?php endif; ?>
