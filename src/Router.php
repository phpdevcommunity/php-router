<?php


namespace DevCoder;

use Exception;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class Router
 * @package DevCoder\Routing
 */
class Router implements RouterInterface
{
    const NO_ROUTE = 1;

    /**
     * @var Route[]
     */
    protected $routes = [];

    /**
     * Router constructor.
     * @param Route[]
     */
    public function __construct(array $routes = [])
    {
        foreach ($routes as $route) {
            $this->add($route);
        }

    }

    public function add(Route $route): self
    {
        if (!in_array($route, $this->routes)) {
            $this->routes[$route->getName()] = $route;
        }
        return $this;
    }

    /**
     * @param ServerRequestInterface $serverRequest
     * @return Route
     * @throws Exception
     */
    public function match(ServerRequestInterface $serverRequest): Route
    {
        $path = $serverRequest->getUri()->getPath();
        foreach ($this->routes as $route) {

            $varsValues = $route->match($path);
            if ($varsValues === null || !in_array($serverRequest->getMethod(), $route->getMethods())) {
                continue;
            }

            foreach ($varsValues as $key => $value) {
                $route->addVar($key, $value);
            }

            return $route;
        }

        throw new Exception('Aucune route ne correspond à l\'URL', self::NO_ROUTE);
    }

    public function generateUri(string $name, array $parameters = []): string
    {
        if (!array_key_exists($name, $this->routes)) {
            throw new Exception(sprintf('%s name route doesnt exist', $name));
        }

        $route = $this->routes[$name];
        if ($route->hasVars() && empty($parameters)) {
            throw new Exception(sprintf('%s route need parameters: %s', $name, implode(',', $route->getVarsNames()))
            );
        }

        $uri = $route->getPath();
        foreach ($route->getVarsNames() as $variable) {
            $varName = trim($variable, '{\}');
            if (!array_key_exists($varName, $parameters)) {
                throw new Exception(sprintf('%s not found in parameters to generate url', $varName));
            }
            $uri = str_replace($variable, $parameters[$varName], $uri);
        }

        return $uri;
    }
}
