<?php

namespace Webby\Routing;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Interface RouterInterface
 */
interface RouterInterface
{
    /**
     * Generates an absolute URL, e.g. "http://example.com/dir/file".
     */
    const ABSOLUTE_URL = 0;

    /**
     * Generates an absolute path, e.g. "/dir/file".
     */
    const ABSOLUTE_PATH = 1;

    /**
     * @param Route $route
     * @return RouterInterface
     */
    public function addRoute(Route $route) : RouterInterface;

    /**
     * @param ServerRequestInterface $serverRequest
     * @return null|Route
     * @throws \Exception if no found route.
     */
    public function match(ServerRequestInterface $serverRequest) : Route;

    /**
     * @param string $name
     * @param array $substitutions
     * @param array $options
     * @return string
     * @throws \Exception if unable to generate the given URI.
     */
    public function generateUri(string $name, array $parameters = [], $referenceType = self::ABSOLUTE_PATH) : string;
}