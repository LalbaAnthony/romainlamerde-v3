<?php

use App\Component;

?>

<div class="error-container">
    <h1 class="error-title">404</h1>
    <p class="error-description">Oups! Il semblerait que la page que vous cherchez n'existe pas.</p>
    <?= Component::display('button', ['href' => APP_URL , 'label' => 'Rentrer à la maison', 'color' => 'dark'], ['css' => true]) ?>
</div>


<style>
    .error-container {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        gap: 1rem;
        margin: 2rem;
        min-height: 50vh;
    }

    .error-title {
        font-size: 5rem;
    }

    .error-description {
        font-size: 20px;
    }

    .error-link {
        font-size: 20px;
        color: var(--red);
        margin: 0;
        margin-top: 20px;
    }
</style>