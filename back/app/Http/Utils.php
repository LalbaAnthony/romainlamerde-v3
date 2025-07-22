<?php

namespace App\Http;

trait Utils
{
    const VIEWS_PATH = __DIR__ . '/../../ressources/views/';
    const DEFAULT_HEADER = [
        'methods' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
        'origin' => '*',
        'cache' => 0,
    ];

    /**
     * Redirect to a different page
     * 
     * @return void
     */
    public static function redirect(string $uri = '/'): void
    {
        if (headers_sent()) return;

        header('Location: ' . APP_URL . $uri);
        exit;
    }

    /**
     * Send a response with headers and status code
     * 
     * @param int $status
     * @param mixed $data
     * @param array $headers
     * @return void
     */
    public function response(int $status, array $headers = []): void
    {
        if (headers_sent()) die('Headers already sent. Cannot send response.');

        $headers = array_merge(self::DEFAULT_HEADER, $headers);

        http_response_code($status);

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
            if (isset($headers['json']) && $headers['json']) {
                header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');
                header('Content-Type: application/json');
            }
        }

        exit;
    }

    /**
     * Send a JSON
     * 
     * @param string $name
     * @param mixed $data
     * @return void
     */
    public function json(mixed $data = null, int $status = 200, array $headers = []): void
    {
        if (headers_sent()) die('Headers already sent. Cannot send JSON response.');

        if ($data === null) $this->response(204, ['json' => true]);

        echo json_encode($data);

        $this->response($status, array_merge(['json' => true], $headers));
    }

    /**
     * Render a view
     * 
     * @param string $name
     * @param mixed $data
     * @return void
     */
    public function view(string $name, mixed $data = null, int $status = 200, array $headers = []): void
    {
        if (headers_sent()) die('Headers already sent. Cannot render view.');

        $path = self::VIEWS_PATH . $name . '.php';

        if (!file_exists($path)) $this->response(404, ['error' => "{$name} has not been found"]);
        if (!is_readable($path)) $this->response(500, ['error' => "{$name} is not readable. Ensure that the file has the correct permissions."]);

        if ($data) extract($data);
        require_once $path;
        if ($data) unset($data);

        $this->response($status, $headers);
    }
}
