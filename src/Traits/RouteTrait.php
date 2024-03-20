<?php

namespace DevCoder\Traits;

use DevCoder\Route as BaseRoute;

trait RouteTrait
{
    /**
     * Creates a new GET route with the given name, path, and handler.
     *
     * @param string $name The name of the route.
     * @param string $path The path of the route.
     * @param mixed $handler The handler for the route.
     * @return BaseRoute The newly created GET route.
     */
    public static function get(string $name, string $path, $handler): BaseRoute
    {
        return new BaseRoute($name, $path, $handler);
    }

    /**
     * Creates a new POST route with the given name, path, and handler.
     *
     * @param string $name The name of the route.
     * @param string $path The path of the route.
     * @param mixed $handler The handler for the route.
     * @return BaseRoute The newly created POST route.
     */
    public static function post(string $name, string $path, $handler): BaseRoute
    {
        return new BaseRoute($name, $path, $handler, ['POST']);
    }

    /**
     * Creates a new PUT route with the given name, path, and handler.
     *
     * @param string $name The name of the route.
     * @param string $path The path of the route.
     * @param mixed $handler The handler for the route.
     * @return BaseRoute The newly created PUT route.
     */
    public static function put(string $name, string $path, $handler): BaseRoute
    {
        return new BaseRoute($name, $path, $handler, ['PUT']);
    }

    /**
     * Creates a new DELETE route with the given name, path, and handler.
     *
     * @param string $name The name of the route.
     * @param string $path The path of the route.
     * @param mixed $handler The handler for the route.
     * @return BaseRoute The newly created DELETE route.
     */
    public static function delete(string $name, string $path, $handler): BaseRoute
    {
        return new BaseRoute($name, $path, $handler, ['DELETE']);
    }
}