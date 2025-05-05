<?php

namespace App;

use Exception;

/**
 * A simple component rendering system.
 *
 * This class loads a PHP component file from a predefined components directory,
 * extracts the provided properties into the local symbol table, and returns the
 * component's rendered output.
 * 
 * Example usage:
 * Component::display('example', ['text' => 'Hello World'], ['css' => true, 'js' => true]);
 */
class Component
{
    /**
     * Base directory for components.
     */
    private const COMPONENTS_PATH = __DIR__ . '/../ressources/components';

    /**
     * Base directory for components.
     */
    private const COMPONENTS_URL = APP_URL . '/ressources/components';

    /**
     * Associative array of loaded CSS files.
     */
    private static array $loadedCss = [];

    /**
     * Associative array of loaded JS files.
     */
    private static array $loadedJs = [];

    /**
     * The component name (which corresponds to the file name without extension).
     */
    private string $name;

    /**
     * Associative array of properties to pass to the component.
     */
    private array $props;

    /**
     * Associative array of parameters to pass to the component.
     */
    private array $params;

    /**
     * The full path to the component file.
     */
    private string $phpPath;

    /**
     * The full path to the component file.
     */
    private string $cssUrl;

    /**
     * The full path to the component file.
     */
    private string $jsUrl;

    /**
     * Component constructor.
     *
     * @param string $name  The name of the component.
     * @param array  $props Associative array of properties.
     * @param array  $params Associative array of component parameters.
     */
    public function __construct(string $name, array $props = [], array $params = [])
    {
        $this->name = $name;
        $this->props = $props;
        $this->params = $params;
        $this->phpPath = self::COMPONENTS_PATH . '/' . $name . '/index.php';
        $this->cssUrl = self::COMPONENTS_URL . '/' . $name . '/style.css';
        $this->jsUrl = self::COMPONENTS_URL . '/' . $name . '/index.js';
    }

    /**
     * Renders the component and returns its output as a string.
     *
     * @return string The rendered component output.
     *
     * @throws Exception If the component file is not found or is not readable.
     */
    public function render(): string
    {
        if (!file_exists($this->phpPath)) throw new Exception("Component not found: " . $this->name);
        if (!is_readable($this->phpPath)) throw new Exception("Component not readable: " . $this->name);

        // Extract data to local variables.
        extract($this->props, EXTR_SKIP);

        ob_start();

        // Include CSS only if it hasn't been printed before
        if (
            $this->cssUrl && !in_array($this->cssUrl, self::$loadedCss) &&
            ((isset($this->params['css']) && $this->params['css']))
        ) {
            echo PHP_EOL . '<link rel="stylesheet" href="' . $this->cssUrl . '">' . PHP_EOL;
            self::$loadedCss[] = $this->cssUrl;
        }

        // Include JS only if it hasn't been printed before
        if (
            $this->jsUrl && !in_array($this->jsUrl, self::$loadedJs) &&
            ((isset($this->params['js']) && $this->params['js']))
        ) {
            echo PHP_EOL . '<script src="' . $this->jsUrl . '"></script>' . PHP_EOL;
            self::$loadedJs[] = $this->jsUrl;
        }

        include $this->phpPath;
        return ob_get_clean();
    }

    /**
     * Magic method to convert the component to a string.
     *
     * This allows the component to be used in string contexts, e.g., with echo.
     *
     * @return string The rendered component.
     */
    public function __toString(): string
    {
        try {
            return $this->render();
        } catch (Exception $e) {
            return '';
        }
    }

    /**
     * Static helper method to quickly render a component.
     *
     * @param string $name  The component name.
     * @param array  $props Associative array of properties.
     * @param array  $params Associative array of component parameters.
     *
     * @throws Exception If the component file is not found or is not readable.
     */
    public static function display(string $name, array $props = [], array $params = []): void
    {
        $component = new self($name, $props, $params);
        echo $component; // Uses the __toString() magic method.
    }
}
