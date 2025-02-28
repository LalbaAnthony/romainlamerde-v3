<?php


namespace App\Http;

class Router
{
    use Utils;

    private $request;
    private $routes;

    public function __construct(Request $request, array $routes)
    {
        $this->request = $request;
        $this->routes = $routes;
    }


    public function dispatch()
    {
        $uri = $this->request->uri;
        $method = $this->request->method;

        if (!isset($this->routes[$uri])) {
            $this->response(404, ['error' => 'Not found']);
        }

        $route = $this->routes[$uri];

        if (!isset($route[$method])) {
            $this->response(405, ['error' => 'Method not allowed']);
        }

        list($controllerName, $action) = explode('@', $route[$method]);

        $controllerClass = 'App\\Controller\\' . $controllerName;

        if (!class_exists($controllerClass)) {
            $this->response(500, ['error' => "{$controllerName} does not exist"]);
        }

        $controller = new $controllerClass();

        if (!method_exists($controller, $action)) {
            $this->response(500, ['error' => "{$action} method does not exist"]);
        }

        call_user_func([$controller, $action], $this->request);
    }
}
