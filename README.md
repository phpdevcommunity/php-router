# PHP Router : A versatile and efficient PHP routing solution designed to streamline route management within PHP applications.

[![Latest Stable Version](http://poser.pugx.org/devcoder-xyz/php-router/v)](https://packagist.org/packages/devcoder-xyz/php-router) [![Total Downloads](http://poser.pugx.org/devcoder-xyz/php-router/downloads)](https://packagist.org/packages/devcoder-xyz/php-router) [![Latest Unstable Version](http://poser.pugx.org/devcoder-xyz/php-router/v/unstable)](https://packagist.org/packages/devcoder-xyz/php-router) [![License](http://poser.pugx.org/devcoder-xyz/php-router/license)](https://packagist.org/packages/devcoder-xyz/php-router) [![PHP Version Require](http://poser.pugx.org/devcoder-xyz/php-router/require/php)](https://packagist.org/packages/devcoder-xyz/php-router)

## Description

PHP Router is a simple and efficient routing library designed for PHP applications. It provides a straightforward way to define routes, handle HTTP requests, and generate URLs. Built with PSR-7 message implementation in mind, it seamlessly integrates with PHP applications.


## Installation

You can install PHP Router via Composer. Just run:

### Composer Require
```
composer require devcoder-xyz/php-router
```

## Requirements

* PHP version 7.4 or above
* Enable URL rewriting on your web server
* Optional: PSR-7 HTTP Message package (e.g., guzzlehttp/psr7)

## Usage

1. **Define Routes**: Define routes using the `Route` class provided by PHP Router.

2. **Initialize Router**: Initialize the `Router` class with the defined routes.

3. **Match Requests**: Match incoming HTTP requests to defined routes.

4. **Handle Requests**: Handle matched routes by executing appropriate controllers or handlers.

5. **Generate URLs**: Generate URLs for named routes.


## Example
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
```

```php
// Define your routes
$routes = [
    new \DevCoder\Route('home_page', '/', [IndexController::class]),
    new \DevCoder\Route('api_articles_collection', '/api/articles', [ArticleController::class, 'getAll']),
    new \DevCoder\Route('api_articles', '/api/articles/{id}', [ArticleController::class, 'get']),
];

// Initialize the router
$router = new \DevCoder\Router($routes, 'http://localhost');

try {
    // Match incoming request
    $route = $router->match(ServerRequestFactory::fromGlobals());
    
    // Handle the matched route
    $handler = $route->getHandler();
    $attributes = $route->getAttributes();
    $controllerName = $handler[0];
    $methodName = $handler[1] ?? null;
    $controller = new $controllerName();
    
    // Invoke the controller method
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

## Features

- Lightweight and easy-to-use
- Supports HTTP method-based routing
- Flexible route definition with attribute constraints
- Exception handling for method not allowed and route not found scenarios

## Route Definition

Routes can be defined using the `Route` class provided by PHP Router. You can specify HTTP methods, attribute constraints, and handler methods for each route.

```php
$route = new \DevCoder\Route('api_articles_post', '/api/articles', [ArticleController::class, 'post'], ['POST']);
$route = new \DevCoder\Route('api_articles_put', '/api/articles/{id}', [ArticleController::class, 'put'], ['PUT']);
```
### Easier Route Definition with Static Methods

To make route definition even simpler and more intuitive, the `RouteTrait` provides static methods for creating different types of HTTP routes. Here's how to use them:

#### Method `get()`

```php
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
```

Example Usage:

```php
$route = Route::get('home', '/', [HomeController::class, 'index']);
```

#### Method `post()`

```php
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
```

Example Usage:

```php
$route = Route::post('submit_form', '/submit', [FormController::class, 'submit']);
```

#### Method `put()`

```php
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
```

Example Usage:

```php
$route = Route::put('update_item', '/item/{id}', [ItemController::class, 'update']);
```

#### Method `delete()`

```php
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
```

Example Usage:

```php
$route = Route::delete('delete_item', '/item/{id}', [ItemController::class, 'delete']);
```

With these static methods, defining routes becomes a breeze, providing a smoother and more efficient way to handle routing in your PHP application.

### Using `where` Constraints in the Route Object

The `Route` object allows you to define constraints on route parameters using the `where` methods. These constraints validate and filter parameter values based on regular expressions. Here's how to use them:

#### Method `whereNumber()`

This method applies a numeric constraint to the specified route parameters.

```php
/**
 * Sets a number constraint on the specified route parameters.
 *
 * @param mixed ...$parameters The route parameters to apply the constraint to.
 * @return self The updated Route instance.
 */
public function whereNumber(...$parameters): self
{
    $this->assignExprToParameters($parameters, '[0-9]+');
    return $this;
}
```

Example Usage:

```php
$route = (new Route('example', '/example/{id}'))->whereNumber('id');
```

#### Method `whereSlug()`

This method applies a slug constraint to the specified route parameters, allowing alphanumeric characters and hyphens.

```php
/**
 * Sets a slug constraint on the specified route parameters.
 *
 * @param mixed ...$parameters The route parameters to apply the constraint to.
 * @return self The updated Route instance.
 */
public function whereSlug(...$parameters): self
{
    $this->assignExprToParameters($parameters, '[a-z0-9-]+');
    return $this;
}
```

Example Usage:

```php
$route = (new Route('article', '/article/{slug}'))->whereSlug('slug');
```

#### Method `whereAlphaNumeric()`

This method applies an alphanumeric constraint to the specified route parameters.

```php
/**
 * Sets an alphanumeric constraint on the specified route parameters.
 *
 * @param mixed ...$parameters The route parameters to apply the constraint to.
 * @return self The updated Route instance.
 */
public function whereAlphaNumeric(...$parameters): self
{
    $this->assignExprToParameters($parameters, '[a-zA-Z0-9]+');
    return $this;
}
```

Example Usage:

```php
$route = (new Route('user', '/user/{username}'))->whereAlphaNumeric('username');
```

#### Method `whereAlpha()`

This method applies an alphabetic constraint to the specified route parameters.

```php
/**
 * Sets an alphabetic constraint on the specified route parameters.
 *
 * @param mixed ...$parameters The route parameters to apply the constraint to.
 * @return self The updated Route instance.
 */
public function whereAlpha(...$parameters): self
{
    $this->assignExprToParameters($parameters, '[a-zA-Z]+');
    return $this;
}
```

Example Usage:

```php
$route = (new Route('category', '/category/{name}'))->whereAlpha('name');
```

#### Method `where()`

This method allows you to define a custom constraint on a specified route parameter.

```php
/**
 * Sets a custom constraint on the specified route parameter.
 *
 * @param string $parameter The route parameter to apply the constraint to.
 * @param string $expression The regular expression constraint.
 * @return self The updated Route instance.
 */
public function where(string $parameter, string $expression): self
{
    $this->wheres[$parameter] = $expression;
    return $this;
}
```

Example Usage:

```php
$route = (new Route('product', '/product/{code}'))->where('code', '\d{4}');
```

By using these `where` methods, you can apply precise constraints on your route parameters, ensuring proper validation of input values.

## Generating URLs

Generate URLs for named routes using the `generateUri` method.

```php
echo $router->generateUri('home_page'); // /
echo $router->generateUri('api_articles', ['id' => 1]); // /api/articles/1
echo $router->generateUri('api_articles', ['id' => 1], true); // http://localhost/api/articles/1
```

Ideal for small to medium-sized projects, PHP Router offers simplicity and efficiency in handling routing tasks for PHP applications.