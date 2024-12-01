<?php

namespace SimpleEvents\Core;

use InvalidArgumentException;

class Router
{
    private array $routes = [];

    /**
     * Register a GET route
     */
    public function get(string $uri, callable|array $action): void
    {
        $this->addRoute('GET', $uri, $action);
    }

    /**
     * Register a POST route
     */
    public function post(string $uri, callable|array $action): void
    {
        $this->addRoute('POST', $uri, $action);
    }

    /**
     * Add a route to the routing table
     */
    private function addRoute(string $method, string $uri, callable|array $action): void
    {
        $normalizedUri = $this->normalizeUri($uri);
        $this->routes[$method][$normalizedUri] = $action;
    }

    /**
     * Dispatch the current request
     */
    public function dispatch(): void
    {
        $requestUri = $this->normalizeUri(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '');
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        $action = $this->routes[$requestMethod][$requestUri] ?? null;

        if (!$action) {
            http_response_code(404);
            return;
        }

        try {
            $this->executeAction($action);
        } catch (\Throwable $e) {
            http_response_code(404);
        }
    }

    /**
     * Execute the matched route action
     */
    private function executeAction(callable|array $action): void
    {
        if (is_callable($action)) {
            $action();
        } elseif (is_array($action)) {
            $this->executeControllerAction($action);
        } else {
            throw new InvalidArgumentException('Invalid action type');
        }
    }

    /**
     * Execute a controller action
     */
    private function executeControllerAction(array $action): void
    {
        [$controller, $method] = $action;

        if (!class_exists($controller) || !method_exists($controller, $method)) {
            throw new InvalidArgumentException('Invalid controller or method');
        }

        (new $controller())->$method();
    }

    /**
     * Normalize a URI by removing trailing slashes
     */
    private function normalizeUri(string $uri): string
    {
        return rtrim($uri, '/');
    }
}
