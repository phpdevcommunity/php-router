<?php

namespace Webbym\Routing;

/**
 * Class Router
 * @package Fady\Routing
 */
class Router
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
    public function addRoute(Route $route)
    {
        if (!in_array($route, $this->routes)) {
            $this->routes[] = $route;
        }
        return $this;
    }

    /**
     * @param $url
     * @return Route|mixed
     * @throws \Exception
     */
    public function getRoute($path)
    {
        /**
         * @var Route $route
         */
        foreach ($this->routes as $route) {

            $varsValues = $route->match($path);
            if (!is_null($varsValues)) {

                if ($route->hasVars()) {

                    $listVars = [];
                    foreach ($varsValues as $key => $value) {
                            $listVars[$key] = $value;
                    }

                    $route->setVars($listVars);
                }
                return $route;
            }
        }

        throw new \Exception('Aucune route ne correspond à l\'URL', self::NO_ROUTE);
    }
}
