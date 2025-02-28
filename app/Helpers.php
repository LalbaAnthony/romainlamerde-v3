<?php

namespace App;

/**
 * Class Helpers
 *
 * This class is used to provide helper public functions.
 */
class Helpers
{

    public function __construct()
    {
        //
    }

    public function env(string $key, mixed $default = null): mixed
    {
        return $_ENV[$key] ?? $default;
    }

    public function currentDate(): string
    {
        return date('Y-m-d');
    }

    public function currentDateTime(): string
    {
        return date('Y-m-d H:i:s');
    }

    public function dd(mixed $var): void
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

    public function slugify(string $string): string
    {
        if (!$string || empty($string)) return '';
        $string = strtolower($string);
        $string = preg_replace('/[^a-z0-9]/', '-', $string);
        $string = preg_replace('/-+/', '-', $string);
        $string = trim($string, '-');
        return $string;
    }
}
