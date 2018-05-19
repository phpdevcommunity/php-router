<?php

namespace Test\Webbym\Routing;



use PHPUnit\Framework\TestCase;
use Webbym\Routing\Route;

/**
 * Class RouterTest
 * @package Test\Webbym\Routing
 */
class RouterTest extends TestCase {


    /**
     * @throws \Exception
     */
    public function testNotFoundRoute()
    {
        $routeWithoutAttribute = new Route('/view/article/', 'App\\Controller\\HomeController', 'home');
        $routeWithAttribute = new Route('/view/article/{article}', 'App\\Controller\\HomeController', 'home');

        $router = (new \Webbym\Routing\Router())
            ->addRoute($routeWithoutAttribute)
            ->addRoute($routeWithAttribute)
        ;

        try {
            $this->assertInstanceOf(Route::class, $router->getRoute('/view/article/1'));
        }catch (\Exception $e) {
            $this->assertInstanceOf(\Exception::class,$e);
        }

        try {
            $this->assertInstanceOf(Route::class, $router->getRoute('/view/file/'));
        }catch (\Exception $e) {
            $this->assertInstanceOf(\Exception::class,$e);
        }

    }

    /**
     * @throws \Exception
     */
    public function testMatchRoute()
    {
        $routeWithoutAttribute = new Route('/view/article/', 'App\\Controller\\HomeController', 'home');
        $routeWithAttribute = new Route('/view/article/{article}', 'App\\Controller\\HomeController', 'home');

        $router = (new \Webbym\Routing\Router())
            ->addRoute($routeWithoutAttribute)
            ->addRoute($routeWithAttribute)
        ;
        $this->assertInstanceOf(Route::class, $router->getRoute('/view/article/'));
        $this->assertInstanceOf(Route::class, $router->getRoute('/view/article/28'));
    }

}