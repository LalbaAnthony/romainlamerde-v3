<?php

namespace App\Http;

trait Html
{
    /**
     * Open the HTML document
     * 
     * @return void
     */
    public static function openHtml(): void
    {
        echo '<!DOCTYPE html>';
        echo '<html lang="' . APP_LANG . '">';
        echo '<head>';
        echo '<meta charset="UTF-8">';
        echo '<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />';
        echo '<meta name="description" content="' . APP_DESCRIPTION . '" />';
        echo '<meta name="author" content="' . APP_AUTHOR . '" />';
        echo '<title>' . APP_NAME_LONG . '</title>';
        echo '<link rel="icon" type="image/x-icon" href="public/favicon.ico" />';
        echo '<link href="' . APP_URL . '/ressources/css/main.css" rel="stylesheet" />';
        echo '<script src="' . APP_URL . '/ressources/js/main.js"></script>';
        echo '<link rel="preconnect" href="https://fonts.googleapis.com">';
        echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
        echo '<link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">';
        echo '</head>';
        echo '<body>';
    }

    /**
     * Close the HTML document
     * 
     * @return void
     */
    public static function closeHtml(): void
    {
        echo '</body>';
        echo '</html>';
    }
}
