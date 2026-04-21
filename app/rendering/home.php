<?php

declare(strict_types=1);

$content = fetch_content_by_slug($app['db'], 'kr-world');

if ($content === null) {
    throw new RuntimeException('Contenuto home non trovato nel database.');
}

return $content;
