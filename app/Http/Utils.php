<?php

namespace App\Http;

trait Utils
{
    public function response(int $status, mixed $data): never
    {
        // TODO : Add Access-Control-Allow-Methods and Access-Control-Allow-Headers
        http_response_code($status);
        header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');
        header("Content-type: application/json; charset=utf-8");
        echo json_encode($data);
        exit;
    }


    public function view(string $name): never
    {
        $path = __DIR__ . "/../view/{$name}.php";

        if (!file_exists($path)) {
            $this->response(500, ['error' => "La vue {$name} n'existe pas"]);
        }

        require $path;
        exit;
    }
}
