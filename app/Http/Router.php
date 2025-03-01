<?php


namespace App\Http;

class Router
{
    use Utils;

    private $request;
    private $routes;

    /**
     * Router constructor.
     * 
     * @param Request $request
     * @param array $routes
     */
    public function __construct(Request $request, array $routes)
    {
        $this->request = $request;
        $this->routes = $routes;
    }

    /**
     * Dispatch the request to the appropriate controller
     * 
     * @return void
     */
    public function dispatch(): void
    {
        $uri = $this->request->uri;
        $method = $this->request->method;

        if (!isset($this->routes[$uri])) {
            $this->redirect('/404');
        }

        $route = $this->routes[$uri];

        if (!isset($route[$method])) {
            $this->response(405, ['error' => 'Method not allowed']);
        }

        list($controllerPath, $action) = explode('@', $route[$method]);

        $controllerClass = 'App\\Controller\\' . $controllerPath;

        if (!class_exists($controllerClass)) {
            $this->response(500, ['error' => "{$controllerPath} does not exist"]);
        }

        $controller = new $controllerClass();

        if (!method_exists($controller, $action)) {
            $this->response(500, ['error' => "{$action} method does not exist"]);
        }

        call_user_func([$controller, $action], $this->request);
    }
}
