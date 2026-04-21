<?php

declare(strict_types=1);

$content = $block['content_json'];
?>
<section class="hero section-shell">
    <?php if (!empty($page['title'])): ?>
        <p class="eyebrow"><?= htmlspecialchars((string) $page['title'], ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>
    <?php if (!empty($content['title'])): ?>
        <h1><?= htmlspecialchars((string) $content['title'], ENT_QUOTES, 'UTF-8') ?></h1>
    <?php endif; ?>
    <?php if (!empty($content['subtitle'])): ?>
        <p class="lead"><?= htmlspecialchars((string) $content['subtitle'], ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>
</section>
