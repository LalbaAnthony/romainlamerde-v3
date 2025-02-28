<?php

namespace App\Http;

class Request
{
    const DEFAULT_METHOD = 'GET';

    public $method;
    public $uri;
    public $body;
    public $params;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'] ?? self::DEFAULT_METHOD;
        $this->uri = str_replace(APP_ROOT, '', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        $this->body = file_get_contents('php://input');
        $this->params = $_GET;
    }

    public function getJson(): mixed
    {
        return json_decode($this->body, true);
    }
}
