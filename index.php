<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

require __DIR__ . '/vendor/autoload.php';

$route = new Webbym\Routing\Route('/view/article/{article}/{test}/', 'App\\Controller\\HomeController', 'home');

$router = (new \Webbym\Routing\Router())->addRoute($route);



var_dump($router->getRoute('/view/article/18/20'));
exit();