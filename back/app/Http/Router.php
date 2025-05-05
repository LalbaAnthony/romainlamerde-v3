<?php


namespace App\Http;

use App\Component;

class Router
{
    use Utils;
    use Html;

    private $request;
    private $routes;
    private $route;

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
     * Find the route based of URI and method
     * 
     * @return void
     */
    public function find(): void
    {
        $uri = $this->request->uri;
        $method = $this->request->method;

        if (!isset($this->routes[$uri])) {
            $this::redirect('/404');
        }

        $route = $this->routes[$uri];

        if (!isset($route[$method])) {
            $this->response(405, ['error' => 'Method not allowed. Allowed methods: ' . implode(', ', array_keys($route))]);
        }

        $this->route = $route[$method];

        if (!isset($this->route['type']) && !in_array($this->route['type'], ['view', 'api'])) {
            $this->response(500, ['error' => 'Invalid route type']);
        }
    }

    /**
     * Run the current route
     * 
     * @return void
     */
    public function execute(): void
    {
        if (!$this->route || empty($this->route)) {
            $this->response(500, ['error' => 'No route found']);
        }

        if (!isset($this->route['path']) || !$this->route['path']) {
            $this->response(500, ['error' => 'No route path found']);
        }

        list($controllerPath, $controllerMethod) = explode('@', $this->route['path']);

        if (!$controllerPath || !$controllerMethod) {
            $this->response(500, ['error' => 'Invalid route path']);
        }

        $controllerClass = 'App\\Controller\\' . $controllerPath;

        if (!class_exists($controllerClass)) {
            $this->response(500, ['error' => "{$controllerClass} does not exist. Check namespaces."]);
        }

        $controller = new $controllerClass();

        if (!method_exists($controller, $controllerMethod)) {
            $this->response(500, ['error' => "{$controllerMethod} method does not exist"]);
        }

        call_user_func([$controller, $controllerMethod], $this->request);
    }

    /**
     * Check if the current route has a hook and trigger it
     * 
     * @return void
     */
    public function hook(string $timing = 'before'): void
    {
        if (!isset($this->route['hooks'][$timing]) || !$this->route['hooks'][$timing]) return;

        if (isset($this->route['hooks'][$timing]['components'])) {
            foreach ($this->route['hooks'][$timing]['components'] as $component) {
                Component::display($component, [], ['css' => true]);
            }
        }
    }

    /**
     * Dispatch the request to the appropriate controller
     * 
     * @return void
     */
    public function dispatch(): void
    {
        $this->find();
        if ($this->route['type'] === 'view') self::openHtml();
        $this->hook('before');
        $this->execute();
        $this->hook('after');
        if ($this->route['type'] === 'view') self::closeHtml();
    }
}
