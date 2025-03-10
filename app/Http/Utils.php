<?php

namespace App\Http;

trait Utils
{
    const VIEWS_PATH = __DIR__ . '/../../ressources/views/';

    /**
     * Redirect to a different page
     * 
     * @return void
     */
    public static function redirect(string $uri = '/'): void
    {
        header('Location: ' . APP_URL . $uri);
        exit;
    }

    /**
     * Send a 404 response
     * 
     * @return void
     */
    public static function error(): void
    {
        header('HTTP/1.1 404 Not Found');
        exit;
    }

    /**
     * Send a JSON response
     * 
     * @param int $status
     * @param mixed $data
     * @param array $headers
     * @return void
     */
    public function response(int $status, mixed $data = null, array $headers = ['methods' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'], 'origin' => '*', 'cache' => 0]): void
    {
        http_response_code($status);
        header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');
        header("Content-type: application/json; charset=utf-8");

        if (is_array($headers) && !empty($headers)) {
            if (isset($headers['method'])) {
                header('Access-Control-Allow-Methods: ' . implode(', ', $headers['methods']));
            }
            if (isset($headers['origin'])) {
                header('Access-Control-Allow-Origin: ' . $headers['origin']);
            }
            if (isset($headers['cache']) && $headers['cache'] > 0) {
                $seconds = $headers['cache'];
                $ts = gmdate("D, d M Y H:i:s", time() + $seconds) . " GMT";
                header("Expires: $ts");
                header("Pragma: cache");
                header("Cache-Control: max-age=$seconds");
            }
        }

        echo json_encode($data);
        exit;
    }

    /**
     * Render a view
     * 
     * @param string $name
     * @param mixed $data
     * @return void
     */
    public function view(string $name, mixed $data = null): void
    {
        $path = self::VIEWS_PATH . $name . '.php';

        if (!file_exists($path)) {
            $this->response(500, ['error' => "{$name} has not been found"]);
        }

        if (!is_readable($path)) {
            $this->response(500, ['error' => "{$name} is not readable. Ensure that the file has the correct permissions."]);
        }

        if ($data) extract($data);
        require_once $path;
        if ($data) unset($data);
    }

    /**
     * Open the HTML document
     * 
     * @return void
     */
    private function openHtml(): void
    {
        echo '<!DOCTYPE html>';
        echo '<html lang="fr">';
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
    private function closeHtml(): void
    {
        echo '</body>';
        echo '</html>';
    }
}
