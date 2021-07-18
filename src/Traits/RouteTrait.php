<?php

namespace DevCoder\Traits;

use DevCoder\Route as BaseRoute;

trait RouteTrait
{
    public static function get(string $name, string $path, array $parameters): BaseRoute
    {
        return new BaseRoute($name, $path, $parameters);
    }

    public static function post(string $name, string $path, array $parameters): BaseRoute
    {
        return new BaseRoute($name, $path, $parameters, ['POST']);
    }

    public static function put(string $name, string $path, array $parameters): BaseRoute
    {
        return new BaseRoute($name, $path, $parameters, ['PUT']);
    }

    public static function delete(string $name, string $path, array $parameters): BaseRoute
    {
        return new BaseRoute($name, $path, $parameters, ['DELETE']);
    }
}