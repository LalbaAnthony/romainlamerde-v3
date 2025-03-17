<?php

namespace App\Http;

class Request
{
    public $method;
    public $uri;
    public $body;
    public $params;

    /**
     * Request constructor.
     */
    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->uri = str_replace(APP_ROOT, '', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        $this->body = file_get_contents('php://input');
        $this->params = $_GET;
    }

    /**
     * @return mixed
     */
    public function json(): mixed
    {
        return json_decode($this->body, true);
    }
}
