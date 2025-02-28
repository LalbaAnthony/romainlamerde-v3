<?php
include_once APP_PATH . '/helpers/slugify.php';

?>

<a href="#<?= slugify($title) ?>" class="section-title-wrap">
    <h4 id="<?= slugify($title) ?>" class="section-title-text">
        <?= $title ?? '' ?>
    </h4>
    <span class="section-title-hastage text-<?= $color ?? '' ?>">#</span>
</a>