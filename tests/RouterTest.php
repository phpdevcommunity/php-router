<?php

namespace Test\PhpDevCommunity;

use PhpDevCommunity\Exception\MethodNotAllowed;
use PhpDevCommunity\Exception\RouteNotFound;
use PhpDevCommunity\Route;
use PhpDevCommunity\Router;
use InvalidArgumentException;
use PhpDevCommunity\UniTester\TestCase;

class RouterTest extends TestCase
{
    private Router $router;

    protected function setUp(): void
    {
        $this->router = (new Router())
            ->add(new Route('home_page', '/home', ['App\\Controller\\HomeController', 'home']))
            ->add(new Route('article_page', '/view/article', ['App\\Controller\\HomeController', 'article']))
            ->add(new Route('article_page_by_id', '/view/article/{id}', ['App\\Controller\\HomeController', 'article']))
            ->add(new Route('article_page_by_id_and_page', '/view/article/{id}/{page}', ['App\\Controller\\HomeController', 'article']));
    }

    protected function tearDown(): void
    {
        // TODO: Implement tearDown() method.
    }

    protected function execute(): void
    {
        $this->testMatchRoute();
        $this->testNotFoundException();
        $this->testMethodNotAllowedException();
        $this->testGenerateUrl();
        $this->testGenerateAbsoluteUrl();
    }

    public function testMatchRoute()
    {
        $route = $this->router->matchFromPath('/view/article/25', 'GET');
        $this->assertInstanceOf(Route::class, $route);

        $this->assertNotEmpty($route->getHandler());
        $this->assertNotEmpty($route->getMethods());
        $this->assertStrictEquals(['id' => '25'], $route->getAttributes());
        $this->assertInstanceOf(Route::class, $this->router->matchFromPath('/home', 'GET'));
    }

    public function testNotFoundException()
    {
        $this->expectException(RouteNotFound::class, function () {
            $this->router->matchFromPath('/homes', 'GET');
        });
    }

    public function testMethodNotAllowedException()
    {
        $this->expectException(MethodNotAllowed::class, function () {
            $this->router->matchFromPath('/home', 'PUT');
        });
    }

    public function testGenerateUrl()
    {
        $urlHome = $this->router->generateUri('home_page');
        $urlArticle = $this->router->generateUri('article_page');
        $urlArticleWithParam = $this->router->generateUri('article_page_by_id', ['id' => 25]);
        $routeArticleWithParams = $this->router->generateUri('article_page_by_id_and_page', ['id' => 25, 'page' => 3]);

        $this->assertStrictEquals($urlHome, '/home');
        $this->assertStrictEquals($urlArticle, '/view/article');
        $this->assertStrictEquals($urlArticleWithParam, '/view/article/25');
        $this->assertStrictEquals($routeArticleWithParams, '/view/article/25/3');

        $this->expectException(InvalidArgumentException::class, function () {
            $this->router->generateUri('article_page_by_id_and_page', ['id' => 25]);
        });
    }

    public function testGenerateAbsoluteUrl()
    {
        $urlHome = $this->router->generateUri('home_page', [], true);
        $urlArticle = $this->router->generateUri('article_page', [], true);
        $urlArticleWithParam = $this->router->generateUri('article_page_by_id', ['id' => 25], true);
        $routeArticleWithParams = $this->router->generateUri('article_page_by_id_and_page', ['id' => 25, 'page' => 3], true);

        $this->assertStrictEquals($urlHome, 'http://localhost/home');
        $this->assertStrictEquals($urlArticle, 'http://localhost/view/article');
        $this->assertStrictEquals($urlArticleWithParam, 'http://localhost/view/article/25');
        $this->assertStrictEquals($routeArticleWithParams, 'http://localhost/view/article/25/3');

        $this->expectException(InvalidArgumentException::class, function () {
            $this->router->generateUri('article_page_by_id_and_page', ['id' => 25], true);
        });
    }

}
