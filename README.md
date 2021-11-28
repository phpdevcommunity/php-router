# A simple Router for PHP App using PSR-7 message implementation

## Installation

Use [Composer](https://getcomposer.org/)

### Composer Require
```
composer require devcoder-xyz/php-router
```

## Requirements

* PHP version 7.3
* Enable URL rewriting on your web server
* Need package for PSR-7 HTTP Message
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

$router = new \DevCoder\Router([
    new \DevCoder\Route('home_page', '/', [IndexController::class]),
    new \DevCoder\Route('api_articles_collection', '/api/articles', [ArticleController::class, 'getAll']),
    new \DevCoder\Route('api_articles', '/api/articles/{id}', [ArticleController::class, 'get']),
]);
```
##Example
$_SERVER['REQUEST_URI'] = '/api/articles/2'
$_SERVER['REQUEST_METHOD'] = 'GET'
```php
try {
    // Example
    // \Psr\Http\Message\ServerRequestInterface
    //$route = $router->match(ServerRequestFactory::fromGlobals());
    // OR

    // $_SERVER['REQUEST_URI'] = '/api/articles/2'
    // $_SERVER['REQUEST_METHOD'] = 'GET'
    $route = $router->matchFromPath($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);

    $parameters = $route->getParameters();
    // $arguments = ['id' => 2]
    $arguments = $route->getVars();

    $controllerName = $parameters[0];
    $methodName = $parameters[1] ?? null;

    $controller = new $controllerName();
    if (!is_callable($controller)) {
        $controller =  [$controller, $methodName];
    }

    echo $controller(...array_values($arguments));

} catch (\Exception $exception) {
    header("HTTP/1.0 404 Not Found");
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
```

Ideal for small project
Simple and easy!
[https://github.com/devcoder-xyz/php-router](https://github.com/devcoder-xyz/php-router)
