<?php

namespace Test\DevCoder;

use PHPUnit\Framework\TestCase;
use DevCoder\Route;

class RouteTest extends TestCase {

    public function testNotMatchRoute()
    {
        $routeWithoutAttribute = new Route('view_articles','/view/article/', ['App\\Controller\\HomeController', 'home']);
        $routeWithAttribute = new Route('view_article','/view/article/{article}', ['App\\Controller\\HomeController', 'home']);

        $this->assertFalse($routeWithoutAttribute->match('/view/article/1'));
        $this->assertFalse($routeWithAttribute->match('/view/article/'));
    }

    public function testMatchRoute()
    {
        $routeWithAttribute = new Route('view_article','/view/article/{article}', ['App\\Controller\\HomeController', 'home']);
        $routeWithAttributes = new Route('view_article_page','/view/article/{article}/{page}', ['App\\Controller\\HomeController', 'home']);
        $routeWithoutAttribute = new Route('view_articles','/view/article', ['App\\Controller\\HomeController', 'home']);

        $this->assertTrue($routeWithAttribute->match('/view/article/1'));
        $this->assertTrue($routeWithAttributes->match('/view/article/1/24'));
        $this->assertTrue($routeWithoutAttribute->match('/view/article/'));
    }

    public function testException()
    {
        $this->expectException(\InvalidArgumentException::class);
        new Route('view_articles','/view', ['App\\Controller\\HomeController', 'home'], []);
    }

    public function testWheres()
    {
        $routes = [
            Route::get('blog.show', '/blog/{id}', function () {})->whereNumber('id'),
            Route::get('blog.show', '/blog/{slug}', function () {})->whereSlug('slug'),
            Route::get('blog.show', '/blog/{slug}/{id}', function () {})
                ->whereNumber('id')
                ->whereSlug('slug'),
            Route::get('invoice.show', '/invoice/{number}', function () {})->whereAlphaNumeric('number'),
            Route::get('invoice.show', '/invoice/{number}', function () {})->whereAlpha('number'),
        ];
        $this->assertTrue($routes[0]->match('/blog/1'));
        $this->assertFalse($routes[0]->match('/blog/F1'));

        $this->assertTrue($routes[1]->match('/blog/title-of-article'));
        $this->assertFalse($routes[1]->match('/blog/title_of_article'));

        $this->assertTrue($routes[2]->match('/blog/title-of-article/12'));

        $this->assertTrue($routes[3]->match('/invoice/F0004'));

        $this->assertFalse($routes[4]->match('/invoice/F0004'));
        $this->assertTrue($routes[4]->match('/invoice/FROUIAUI'));
    }
}
