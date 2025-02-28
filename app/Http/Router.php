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
            $this->response(404, ['error' => 'Route non trouvée']);
        }

        $route = $this->routes[$uri];

        if (!isset($route[$method])) {
            $this->response(405, ['error' => 'Méthode non autorisée']);
        }

        list($controllerName, $action) = explode('@', $route[$method]);

        $controllerClass = 'App\\Controller\\' . $controllerName;

        if (!class_exists($controllerClass)) {
            $this->response(500, ['error' => "Le contrôleur {$controllerName} n'existe pas"]);
        }

        $controller = new $controllerClass();

        if (!method_exists($controller, $action)) {
            $this->response(500, ['error' => "La méthode {$action} n'existe pas dans le contrôleur {$controllerName}"]);
        }

        call_user_func([$controller, $action], $this->request);
    }
}
