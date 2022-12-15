<?php

namespace DevCoder\Traits;

use DevCoder\Route as BaseRoute;

trait RouteTrait
{
    public static function get(string $name, string $path, $handler): BaseRoute
    {
        return new BaseRoute($name, $path, $handler);
    }

    public static function post(string $name, string $path, $handler): BaseRoute
    {
        return new BaseRoute($name, $path, $handler, ['POST']);
    }

    public static function put(string $name, string $path, $handler): BaseRoute
    {
        return new BaseRoute($name, $path, $handler, ['PUT']);
    }

    public static function delete(string $name, string $path, $handler): BaseRoute
    {
        return new BaseRoute($name, $path, $handler, ['DELETE']);
    }
}