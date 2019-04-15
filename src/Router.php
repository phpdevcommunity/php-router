<?php


namespace Webby\Routing;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Class Router
 * @package Fady\Routing
 */
class Router implements RouterInterface
{
    const NO_ROUTE = 1;

    /**
     * @var array
     */
    protected $routes = [];


    /**
     * Router constructor.
     * @param RouteBuilderInterface|null $routeBuilder
     */
    public function __construct(RouteBuilderInterface $routeBuilder = null)
    {
        if (!is_null($routeBuilder)) {

            /**
             * @var Route $route
             */
            foreach ($routeBuilder->routes() as $route) {

                $this->addRoute($route);
            }

        }

    }

    /**
     * @param Route $route
     * @return Router
     */
    public function addRoute(Route $route): RouterInterface
    {
        if (!in_array($route, $this->routes)) {
            $this->routes[$route->getName()] = $route;
        }
        return $this;
    }

    /**
     * @param ServerRequestInterface $serverRequest
     * @return Route
     * @throws \Exception
     */
    public function match(ServerRequestInterface $serverRequest): Route
    {
        /**
         * @var Route $route
         */
        foreach ($this->routes as $route) {

            $serverParams = $serverRequest->getServerParams();
            $varsValues = $route->match($serverRequest->getUri()->getPath());
            if (array_key_exists('PATH_INFO', $serverParams)) {
                $varsValues = $route->match($serverParams['PATH_INFO']);
            }

            if (is_null($varsValues)) {
                continue;
            }

            if ($route->hasVars()) {
                $listVars = [];
                foreach ($varsValues as $key => $value) {
                    $listVars[$key] = $value;
                }

                $route->setVars($listVars);
            }

            return $route;
        }

        throw new \Exception('Aucune route ne correspond à l\'URL', self::NO_ROUTE);
    }


    /**
     * @param string $name
     * @param array $parameters
     * @param int $referenceType
     * @return string
     * @throws \Exception
     */
    public function generateUri(string $name, array $parameters = [], $referenceType = self::ABSOLUTE_PATH): string
    {
        if (!array_key_exists($name, $this->routes)) {

            throw new \Exception(sprintf('%s name route doesnt exist', $name));
        }

        /**
         * @var Route $route
         */
        $route = $this->routes[$name];
        $uri = $route->getPath();

        if ($route->hasVars()) {
            if (empty($parameters)) {
                throw new \Exception(
                    sprintf(
                        '%s route need parameters: %s',
                        $name,
                        implode(',', $route->getVarsNames())
                    )
                );
            }

            foreach ($route->getVarsNames() as $variable) {
                $varName = trim($variable, '{\}');
                if (!array_key_exists($varName, $parameters)) {
                    throw new \Exception(sprintf('%s not found in parameters to generate url', $varName));
                }
                $uri = str_replace($variable, $parameters[$varName], $uri);
            }

        }

        return $uri;

    }

}
