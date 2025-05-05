<button
    class="button <?= $color ?? 'light' ?> <?= $outline ? 'outline' : ' ' ?>"
    <?= $href ? 'onclick="window.location.href=\'' . $href . '\'"' : '' ?>>
    <?= $label ?? 'Cliquez' ?>
</button>