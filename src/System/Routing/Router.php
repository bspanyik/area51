<?php

namespace Area51\System\Routing;

use Area51\Controller\ControllerInterface;
use Area51\System\Container;
use InvalidArgumentException;
use OutOfBoundsException;

class Router
{
    /** @var Container */
    private $container;

    /** @var Route[] */
    private $routes;

    /** @var Context */
    private $context;

    /** @var Route */
    private $route;

    /** @var array */
    private $matchedParams = [];

    /**
     * @param Container $container
     * @param array $routes
     * @param Context $context
     */
    public function __construct(Container $container, array $routes, Context $context)
    {
        $this->container = $container;
        $this->routes = $routes;
        $this->context = $context;

        $this->route = $this->getRoute();
    }

    /**
     * @return callable
     * @throws InvalidArgumentException
     */
    public function getController(): callable
    {
        $callable = $this->createController($this->route->getController());
        if (!is_callable($callable)) {
            throw new InvalidArgumentException(sprintf('The controller for URI "%s" is not callable. %s', $this->context->getRequestPath(), print_r($callable, true)));
        }

        return $callable;
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        $params = [];

        if ($this->route->hasParameters()) {
            foreach ($this->route->getParameters() as $parameter) {
                if ($this->container->has($parameter)) {
                    $params[] = $this->container->get($parameter);
                } elseif (array_key_exists($parameter, $this->matchedParams)) {
                    $params[] = $this->matchedParams[$parameter];
                } else {
                    throw new InvalidArgumentException(sprintf('Cannot resolve necessary method parameter: %s', $parameter));
                }
            }
        }

        return $params;
    }

    /**
     * @return Route
     */
    private function getRoute(): Route
    {
        $requestMethod = $this->context->getRequestMethod();
        $requestPath   = $this->context->getRequestPath();

        $resource = '/' . explode('/', ltrim($requestPath, '/'))[0];
        if (!isset($this->routes[$resource])) {
            throw new OutOfBoundsException(sprintf('No controller found for URI "%s"', $requestPath));
        }

        /** @var Route $route */
        foreach ($this->routes[$resource] as $route) {
            if ($route->getMethod() === $requestMethod && preg_match($route->getPattern(), $requestPath, $this->matchedParams)) {
                return $route;
            }
        }

        throw new OutOfBoundsException(sprintf('No controller found for URI "%s"', $requestPath));
    }

    /**
     * @param array $controllerArray
     * @return callable
     * @throws InvalidArgumentException
     */
    private function createController(array $controllerArray): callable
    {
        if (!is_array($controllerArray) || count($controllerArray) < 2) {
            throw new InvalidArgumentException(sprintf('Invalid controller, not array, or method missing. %s', print_r($controllerArray, true)));
        }

        list($controller, $action) = $controllerArray;

        if (!class_exists($controller)) {
            throw new InvalidArgumentException(sprintf('Controller class "%s" does not exist.', $controller));
        }

        return [$this->instantiateController($controller), $action];
    }

    /**
     * @param string $class
     * @return ControllerInterface
     */
    private function instantiateController(string $class): ControllerInterface
    {
        if ($this->container->has($class)) {
            return $this->container->get($class);
        }

        return new $class();
    }

}
