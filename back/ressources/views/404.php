<?php

use App\Component;

?>

<main>
    <div class="error-container">
        <h1 class="error-title">404</h1>
        <p class="error-description">Oups! Il semblerait que la page que tu cherche n'existe pas &#x1F614;</p>
        <?= Component::display('button', ['href' => APP_URL, 'label' => 'Rentrer à la maison', 'color' => 'light', 'outline' => true], ['css' => true]) ?>
    </div>
</main>

<style>
    .error-container {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        gap: 1rem;
    }

    .error-title {
        font-size: 5rem;
    }

    .error-description {
        text-align: center;
        font-size: 20px;
    }

    .error-link {
        font-size: 20px;
        color: var(--red);
        margin: 0;
        margin-top: 20px;
    }
</style>