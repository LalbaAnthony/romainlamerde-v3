<?php

namespace App\Http;

trait Utils
{
    const VIEWS_PATH = __DIR__ . '/../../ressources/views/';
    const LAYOUTS_PATH = __DIR__ . '/../../ressources/layouts/';

    public function response(int $status, mixed $data): never
    {
        // TODO : Add Access-Control-Allow-Methods and Access-Control-Allow-Headers
        http_response_code($status);
        header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');
        header("Content-type: application/json; charset=utf-8");
        echo json_encode($data);
        exit;
    }

    public function view(string $name): void
    {
        $path = self::VIEWS_PATH . $name . '.php';

        if (!file_exists($path)) {
            $this->response(500, ['error' => "{$name} has not been found"]);
        }

        if (!is_readable($path)) {
            $this->response(500, ['error' => "{$name} is not readable. Ensure that the file has the correct permissions."]);
        }

        require_once $path;
    }

    public function layout(string $name): void
    {
        $path = self::LAYOUTS_PATH . $name . '.php';

        if (!file_exists($path)) {
            $this->response(500, ['error' => "{$name} has not been found"]);
        }

        if (!is_readable($path)) {
            $this->response(500, ['error' => "{$name} is not readable. Ensure that the file has the correct permissions."]);
        }

        require_once $path;
    }
}
