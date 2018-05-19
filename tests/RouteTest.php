<?php

namespace Test\Webbym\Routing;



use PHPUnit\Framework\TestCase;
use Webbym\Routing\Route;

/**
 * Class RouterTest
 * @package Test\Webbym\Routing
 */
class RouteTest extends TestCase {



    public function testNotMatchRoute()
    {
        $routeWithoutAttribute = new Route('/view/article/', 'App\\Controller\\HomeController', 'home');
        $routeWithAttribute = new Route('/view/article/{article}', 'App\\Controller\\HomeController', 'home');

        $this->assertNull($routeWithoutAttribute->match('/view/article/1'));
        $this->assertNull($routeWithAttribute->match('/view/article/'));
    }

    public function testMatchRoute()
    {
        $routeWithAttribute = new Route('/view/article/{article}', 'App\\Controller\\HomeController', 'home');
        $routeWithAttributes = new Route('/view/article/{article}/{page}', 'App\\Controller\\HomeController', 'home');
        $routeWithoutAttribute = new Route('/view/article/', 'App\\Controller\\HomeController', 'home');

        $this->assertInternalType('array',$routeWithAttribute->match('/view/article/1'));
        $this->assertInternalType('array',$routeWithAttributes->match('/view/article/1/24'));
        $this->assertInternalType('array',$routeWithoutAttribute->match('/view/article'));
    }

}