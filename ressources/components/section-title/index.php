<?php
use App\Helpers;

?>

<a href="#<?= Helpers::slugify($title) ?>" class="section-title-wrap">
    <h4 id="<?= Helpers::slugify($title) ?>" class="section-title-text">
        <?= $title ?? '' ?>
    </h4>
    <span class="section-title-hastage text-<?= $color ?? '' ?>">#</span>
</a>