<?php

declare(strict_types=1);

namespace PhpDevCommunity;

use PhpDevCommunity\Exception\MethodNotAllowed;
use PhpDevCommunity\Exception\RouteNotFound;
use Psr\Http\Message\ServerRequestInterface;

interface RouterInterface
{
    /**
     * @param ServerRequestInterface $serverRequest
     * @return Route
     * @throws RouteNotFound if no found route.
     * @throws MethodNotAllowed if method not allowed.
     */
    public function match(ServerRequestInterface $serverRequest): Route;

    /**
     * @param string $path
     * @param string $method
     * @return Route
     * @throws RouteNotFound if no found route.
     * @throws MethodNotAllowed if method not allowed.
     */
    public function matchFromPath(string $path, string $method): Route;

    /**
     * @param string $name
     * @param array $parameters
     * @return string
     * @throws \InvalidArgumentException if unable to generate the given URI.
     */
    public function generateUri(string $name, array $parameters = []): string;
}
