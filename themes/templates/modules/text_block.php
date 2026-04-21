<?php

declare(strict_types=1);

$content = $block['content_json'];
?>
<?php if (!empty($content['body'])): ?>
    <section class="text-section section-shell">
        <article class="text-card">
            <p><?= htmlspecialchars((string) $content['body'], ENT_QUOTES, 'UTF-8') ?></p>
        </article>
    </section>
<?php endif; ?>
