# A simple Router for PHP App using PSR-7 message implementation

[![Latest Stable Version](http://poser.pugx.org/devcoder-xyz/php-router/v)](https://packagist.org/packages/devcoder-xyz/php-router) [![Total Downloads](http://poser.pugx.org/devcoder-xyz/php-router/downloads)](https://packagist.org/packages/devcoder-xyz/php-router) [![Latest Unstable Version](http://poser.pugx.org/devcoder-xyz/php-router/v/unstable)](https://packagist.org/packages/devcoder-xyz/php-router) [![License](http://poser.pugx.org/devcoder-xyz/php-router/license)](https://packagist.org/packages/devcoder-xyz/php-router) [![PHP Version Require](http://poser.pugx.org/devcoder-xyz/php-router/require/php)](https://packagist.org/packages/devcoder-xyz/php-router)

## Installation

Use [Composer](https://getcomposer.org/)

### Composer Require
```
composer require devcoder-xyz/php-router
```

## Requirements

* PHP version 7.4
* Enable URL rewriting on your web server
* Optional : Need package for PSR-7 HTTP Message
  (example : guzzlehttp/psr7 )

**How to use ?**

```php
<?php
class IndexController {

    public function __invoke()
    {
        return 'Hello world!!';
    }
}

class ArticleController {

    public function getAll()
    {
        // db get all post
        return json_encode([
            ['id' => 1],
            ['id' => 2],
            ['id' => 3]
        ]);
    }

    public function get(int $id)
    {
        // db get post by id
        return json_encode(['id' => $id]);
    }

    public function put(int $id)
    {
        // db edited post by id
        return json_encode(['id' => $id]);
    }

    public function post()
    {
        // db create post
        return json_encode(['id' => 4]);
    }
}

$routes = [
    new \DevCoder\Route('home_page', '/', [IndexController::class]),
    new \DevCoder\Route('api_articles_collection', '/api/articles', [ArticleController::class, 'getAll']),
    new \DevCoder\Route('api_articles', '/api/articles/{id}', [ArticleController::class, 'get']),
];
$router = new \DevCoder\Router($routes, 'http://localhost');
```

## Example

```php
try {
    // Example
    
    // \Psr\Http\Message\ServerRequestInterface
    $route = $router->match(ServerRequestFactory::fromGlobals());
    // OR
    
    // $_SERVER['REQUEST_URI'] = '/api/articles/2'
    // $_SERVER['REQUEST_METHOD'] = 'GET'
    $route = $router->matchFromPath($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);

    $handler = $route->getHandler();
    // $attributes = ['id' => 2]
    $attributes = $route->getAttributes();

    $controllerName = $handler[0];
    $methodName = $handler[1] ?? null;

    $controller = new $controllerName();
    if (!is_callable($controller)) {
        $controller =  [$controller, $methodName];
    }

    echo $controller(...array_values($attributes));

} catch (\DevCoder\Exception\MethodNotAllowed $exception) {
    header("HTTP/1.0 405 Method Not Allowed");
    exit();
} catch (\DevCoder\Exception\RouteNotFound $exception) {
    header("HTTP/1.0 404 Not Found");
    exit();
}
```
How to Define Route methods
```php
$route = new \DevCoder\Route('api_articles_post', '/api/articles', [ArticleController::class, 'post'], ['POST']);
$route = new \DevCoder\Route('api_articles_put', '/api/articles/{id}', [ArticleController::class, 'put'], ['PUT']);
/**
* ---- OR -----
*/
$route = \DevCoder\Route::post('api_articles_post', '/api/articles', [ArticleController::class, 'post']);
$route = \DevCoder\Route::put('api_articles_put', '/api/articles/{id}', [ArticleController::class, 'put']);
```
Generating URLs
```php
echo $router->generateUri('home_page');
// /
echo $router->generateUri('api_articles', ['id' => 1]);
// /api/articles/1

echo $router->generateUri('api_articles', ['id' => 1], true);
// http://localhost/api/articles/1
```

Ideal for small project.
Simple and easy!
[https://github.com/devcoder-xyz/php-router](https://github.com/devcoder-xyz/php-router)
