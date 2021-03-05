<?php

use LiteApp\Middleware\ControllerMiddleware;

require 'vendor/autoload.php';
error_reporting(E_ALL);
ini_set("display_errors", 1);


class HomeController
{

    public function __invoke()
    {
        return '<p>Hello world</p>';
    }
}

class ArticleController
{

    public function list()
    {
        return '<ul>
                    <li>Article 1</li>
                    <li>Article 2</li>
                </ul>';
    }

    public function view(int $id)
    {
        return "<p>Article $id</p>";
    }
}


//$router = new \DevCoder\Router([
//    new \DevCoder\Route('home_page', '/', function () {
//        return '<p>Hello world</p>';
//    })
//]);

$router = new \DevCoder\Router([
    new \DevCoder\Route('home_page', '/', function () {
        return '<p>Hello world</p>';
    }),
//    new \DevCoder\Route('home_page', '/', [HomeController::class]),
    new \DevCoder\Route('articles', '/articles', [ArticleController::class, 'list']),
    new \DevCoder\Route('article_view', '/articles/{id}', [ArticleController::class, 'view'])
]);

try {

    $route = $router->matchFromPath('/articles/1', 'GET');

    $callback = $route->getCallback();
    $arguments = $route->getVars();

//
    if (is_array($callback)) {

        $controller = new $callback[0]();
        if (is_callable($controller)) {
            $callback = $controller;
        }else {
            $action = $callback[1] ?? null;
            if (!method_exists($controller, $action)) {

                if ($action === null) {
                    throw new \BadMethodCallException(sprintf('Please use a Method on class %s.', get_class($controller)));
                }
                throw new \BadMethodCallException(sprintf('Method "%s" on class %s does not exist.', $action, get_class($controller)));
            }

            $callback = [$controller, $action];
        }

    }

    echo $callback(...array_values($arguments));

} catch (\BadMethodCallException $e) {
    throw  $e;
} catch (\Exception $e) {
    header("HTTP/1.0 404 Not Found");
}


