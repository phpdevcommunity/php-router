<?php

declare(strict_types=1);

namespace DevCoder;

use DevCoder\Exception\RouteNotFound;
use Psr\Http\Message\ServerRequestInterface;

final class Router implements RouterInterface
{
    private const NO_ROUTE = 404;

    /**
     * @var array<Route>
     */
    private $routes = [];

    /**
     * Router constructor.
     * @param $routes array<Route>
     */
    public function __construct(array $routes = [])
    {
        foreach ($routes as $route) {
            $this->add($route);
        }
    }

    public function add(Route $route): self
    {
        if (in_array($route, $this->routes) === false) {
            $this->routes[$route->getName()] = $route;
        }
        return $this;
    }

    public function match(ServerRequestInterface $serverRequest): Route
    {
        return $this->matchFromPath($serverRequest->getUri()->getPath(), $serverRequest->getMethod());
    }

    public function matchFromPath(string $path, string $method): Route
    {
        foreach ($this->routes as $route) {
            if ($route->match($path, $method) === false) {
                continue;
            }
            return $route;
        }

        throw new RouteNotFound(
            'No route found for ' . $method,
            self::NO_ROUTE
        );
    }

    public function generateUri(string $name, array $parameters = []): string
    {
        if (array_key_exists($name, $this->routes) === false) {
            throw new \InvalidArgumentException(
                sprintf('Unknown %s name route', $name)
            );
        }
        $route = $this->routes[$name];
        if ($route->hasVars() && $parameters === []) {
            throw new \InvalidArgumentException(
                sprintf('%s route need parameters: %s', $name, implode(',', $route->getVarsNames()))
            );
        }
        return self::resolveUri($route, $parameters);
    }

    private static function resolveUri(Route $route, array $parameters): string
    {
        $uri = $route->getPath();
        foreach ($route->getVarsNames() as $variable) {
            $varName = trim($variable, '{\}');
            if (array_key_exists($varName, $parameters) === false) {
                throw new \InvalidArgumentException(
                    sprintf('%s not found in parameters to generate url', $varName)
                );
            }
            $uri = str_replace($variable, $parameters[$varName], $uri);
        }
        return $uri;
    }
}
