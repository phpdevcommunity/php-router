<?php

namespace Test\DevCoder;



use PHPUnit\Framework\TestCase;
use DevCoder\Route;

/**
 * Class RouterTest
 * @package Test\Webbym\Routing
 */
class RouteTest extends TestCase {



    public function testNotMatchRoute()
    {
        $routeWithoutAttribute = new Route('view_articles','/view/article/', ['App\\Controller\\HomeController', 'home']);
        $routeWithAttribute = new Route('view_article','/view/article/{article}', ['App\\Controller\\HomeController', 'home']);

        $this->assertNull($routeWithoutAttribute->match('/view/article/1'));
        $this->assertNull($routeWithAttribute->match('/view/article/'));
    }

    public function testMatchRoute()
    {
        $routeWithAttribute = new Route('view_article','/view/article/{article}', ['App\\Controller\\HomeController', 'home']);
        $routeWithAttributes = new Route('view_article_page','/view/article/{article}/{page}', ['App\\Controller\\HomeController', 'home']);
        $routeWithoutAttribute = new Route('view_articles','/view/article', ['App\\Controller\\HomeController', 'home']);

        $this->assertInternalType('array',$routeWithAttribute->match('/view/article/1'));
        $this->assertInternalType('array',$routeWithAttributes->match('/view/article/1/24'));
        $this->assertInternalType('array',$routeWithoutAttribute->match('/view/article/'));
    }

}