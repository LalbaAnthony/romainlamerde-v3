<?php

use App\Component;

?>

<main>
    <div class="error-container">
        <h1 class="error-title">500</h1>
        <p class="error-description">La c'est la douille ... Une erreur est survenue sur le serveur &#x1F62D;</p>
        <?= Component::display('button', ['href' => APP_URL, 'label' => 'Accueil', 'color' => 'light', 'outline' => true], ['css' => true]) ?>
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