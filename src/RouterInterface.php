<?php

namespace DevCoder;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Interface RouterInterface
 * @package DevCoder\Routing
 */
interface RouterInterface
{
    /**
     * @param ServerRequestInterface $serverRequest
     * @return Route
     * @throws \Exception if no found route.
     */
    public function match(ServerRequestInterface $serverRequest) : Route;

    /**
     * @param string $name
     * @param array $parameters
     * @return string
     * @throws \Exception if unable to generate the given URI.
     */
    public function generateUri(string $name, array $parameters = []) : string;
}