<?php

use App\Component; ?>

<main>
    <section>
        <?php Component::display('section-title', ['title' => 'Ma première section', 'color' => 'red'], ['css' => true]) ?>
        <?php var_dump($quotes); ?>
    </section>
    <section>
        <?php Component::display('section-title', ['title' => 'Ma deuxième section', 'color' => 'orange'], ['css' => true]) ?>
        test
    </section>
</main>