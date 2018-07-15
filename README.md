# PHP-Routing
A simple Router for PHP using PSR-7 message implementation

## Requirements

 * PHP 7.2.0+
 * Enable URL rewriting on your web server
 * Need package for PSR-7 HTTP Message
   (example : guzzlehttp/psr7 )
 
 ## Installation
 
 soon
 
 ## Usage

Simple usage :

``` php
<?php

use Webby\Routing\Route;
use Webby\Routing\Router;

$route = new Route('home_page', '/', HomeController::class, 'indexAction');
$router = (new Router())
            ->addRoute($route);
            
 /**
 * @var ServerRequestInterface $request
 */        
$routeMatching = $router->match($request);

$controller = $routeMatching->getController();
$action = $routeMatching->getAction();

```
