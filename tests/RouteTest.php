<?php

namespace Test\DevCoder;



use DevCoder\Exception\RouteNotFound;
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

        $this->assertFalse($routeWithoutAttribute->match('/view/article/1', 'GET'));
        $this->assertFalse($routeWithoutAttribute->match('/view/article/1', 'PUT'));
        $this->assertFalse($routeWithAttribute->match('/view/article/', 'POST'));
    }

    public function testMatchRoute()
    {
        $routeWithAttribute = new Route('view_article','/view/article/{article}', ['App\\Controller\\HomeController', 'home']);
        $routeWithAttributes = new Route('view_article_page','/view/article/{article}/{page}', ['App\\Controller\\HomeController', 'home']);
        $routeWithoutAttribute = new Route('view_articles','/view/article', ['App\\Controller\\HomeController', 'home']);

        $this->assertTrue($routeWithAttribute->match('/view/article/1', 'GET'));
        $this->assertTrue(!$routeWithAttribute->match('/view/article/1', 'PUT'));
        $this->assertTrue($routeWithAttributes->match('/view/article/1/24','GET'));
        $this->assertTrue($routeWithoutAttribute->match('/view/article/','GET'));
    }

    public function testException()
    {
        $this->expectException(\InvalidArgumentException::class);
        new Route('view_articles','/view', ['App\\Controller\\HomeController', 'home'], []);
    }
}
