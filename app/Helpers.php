<?php

namespace App;

/**
 * Class Helpers
 *
 * This class is used to provide helper public functions.
 */
class Helpers
{

    /**
     * Get current date in Y-m-d format.
     *
     * @return string
     */
    public static function currentDate(): string
    {
        return date('Y-m-d');
    }

    /**
     * Get current date and time in Y-m-d H:i:s format.
     *
     * @return string
     */
    public static function currentDateTime(): string
    {
        return date('Y-m-d H:i:s');
    }

    /**
     * Get current date and time in Y-m-d H:i:s format.
     *
     * @return string
     */
    public static function dd(mixed $var): void
    {
        $args = func_get_args();
        $bt = debug_backtrace();
        $caller = array_shift($bt);

        echo "<pre style='background-color: #ccc; padding: 10px; border-radius: 5px; border: 1px solid #ccc;'>";
        echo "<p><strong>" . $caller['file'] . ":" . $caller['line'] . "</strong></p>";
        foreach ($args as $arg) {
            var_dump($arg);
        }
        echo "</pre>";
    }

    /**
     * Slugify a string.
     *
     * @param string $string
     * @return string
     */
    public static function slugify(string $string): string
    {
        if (!$string || empty($string)) return '';
        $string = strtolower($string);
        $string = preg_replace('/[^a-z0-9]/', '-', $string);
        $string = preg_replace('/-+/', '-', $string);
        $string = trim($string, '-');
        return $string;
    }
}
