<?php

declare(strict_types=1);

namespace DevCoder;

final class UrlGenerator
{
    /**
     * @var \ArrayAccess<Route>
     */
    private $routes;

    public function __construct(\ArrayAccess $routes)
    {
        $this->routes = $routes;
    }

    public function generate(string $name, array $parameters = []): string
    {
        if ($this->routes->offsetExists($name) === false) {
            throw new \InvalidArgumentException(
                sprintf('Unknown %s name route', $name)
            );
        }
        /*** @var Route $route */
        $route = $this->routes[$name];
        if ($route->hasAttributes() === true && $parameters === []) {
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