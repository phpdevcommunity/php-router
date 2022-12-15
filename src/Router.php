<?php

declare(strict_types=1);

namespace DevCoder;

use DevCoder\Exception\MethodNotAllowed;
use DevCoder\Exception\RouteNotFound;
use Psr\Http\Message\ServerRequestInterface;

final class Router implements RouterInterface
{
    private const NO_ROUTE = 404;
    private const METHOD_NOT_ALLOWED = 405;

    private \ArrayObject $routes;
    private UrlGenerator $urlGenerator;

    /**
     * Router constructor.
     * @param $routes array<Route>
     */
    public function __construct(array $routes = [], string $defaultUri = 'http://localhost')
    {
        $this->routes = new \ArrayObject();
        $this->urlGenerator = new UrlGenerator($this->routes, $defaultUri);
        foreach ($routes as $route) {
            $this->add($route);
        }
    }

    public function add(Route $route): self
    {
        $this->routes->offsetSet($route->getName(), $route);
        return $this;
    }

    public function match(ServerRequestInterface $serverRequest): Route
    {
        return $this->matchFromPath($serverRequest->getUri()->getPath(), $serverRequest->getMethod());
    }

    public function matchFromPath(string $path, string $method): Route
    {
        /**
         * @var Route $route
         */
        foreach ($this->routes as $route) {
            if ($route->match($path) === false) {
                continue;
            }

            if (!in_array($method, $route->getMethods())) {
                throw new MethodNotAllowed(
                    'Method Not Allowed : ' . $method,
                    self::METHOD_NOT_ALLOWED
                );
            }
            return $route;
        }

        throw new RouteNotFound(
            'No route found for ' . $path,
            self::NO_ROUTE
        );
    }

    public function generateUri(string $name, array $parameters = [], bool $absoluteUrl = false): string
    {
        return $this->urlGenerator->generate($name, $parameters, $absoluteUrl);
    }

    public function getUrlGenerator(): UrlGenerator
    {
        return $this->urlGenerator;
    }
}
