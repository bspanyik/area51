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

    /** @var Context */
    private $context;

    /** @var Route[] */
    private $routes;

    /** @var Route */
    private $route;

    /**
     * @param Container $container
     * @param Context $context
     * @param array $routes
     */
    public function __construct(Container $container, Context $context, array $routes)
    {
        $this->container = $container;
        $this->context = $context;
        $this->routes = $routes;
    }

    /**
     * @return callable
     * @throws InvalidArgumentException
     */
    public function getController(): callable
    {
        $route = $this->getRoute();

        $callable = $this->createController($route->getController());
        if (!is_callable($callable)) {
           throw new InvalidArgumentException(sprintf('The controller for URI "%s" is not callable. %s', $this->context->getPath(), print_r($callable, true)));
        }

        return $callable;
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        $route = $this->getRoute();

        $params = [];

        if ($route->hasParameters()) {
            foreach ($route->getParameters() as $parameter) {
                if ($this->container->has($parameter)) {
                    $params[] = $this->container->get($parameter);
                }
            }
        }

        preg_match($route->getPattern(), $this->context->getPath(), $matches);
        if (!empty($matches)) {
            $params[] = array_merge($params, $matches);
        }

        return $params;
    }

    /**
     * @return Route
     */
    private function getRoute(): Route
    {
        if (!isset($this->route)) {
            $this->route = $this->prepareRoute();
        }

        return $this->route;
    }

    /**
     * @return Route
     * @throws OutOfBoundsException
     */
    private function prepareRoute(): Route
    {
        $this->route = null;

        $path = $this->context->getPath();
        $resource = '/' . explode('/', ltrim($path, '/'))[0];


        if (!isset($this->routes[$resource])) {
            throw new OutOfBoundsException(sprintf('No controller found for URI "%s"', $path));
        }

        /** @var Route $route */
        foreach ($this->routes[$resource] as $route) {
            if ($route->getMethod() === $this->context->getMethod() && preg_match($route->getPattern(), $path, $matches)) {
                return $route;
            }
        }

        throw new OutOfBoundsException(sprintf('No controller found for URI "%s"', $path));
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
