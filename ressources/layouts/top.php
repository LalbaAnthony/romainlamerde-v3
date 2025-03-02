<?php

use App\Component;

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="<?= APP_DESCRIPTION ?>" />
    <meta name="author" content="<?= APP_AUTHOR ?>" />
    <title><?= APP_NAME_LONG ?></title>
    <link rel="icon" type="image/x-icon" href="public/favicon.ico" />
    <link href="<?= APP_URL ?>/ressources/css/main.css" rel="stylesheet" />
    <script src="<?= APP_URL ?>/ressources/js/main.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
</head>

<body>
    <?php Component::display('header', [], ['css' => true, 'js' => true]); ?>
