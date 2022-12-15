<?php

namespace Test\DevCoder;

use DevCoder\Exception\MethodNotAllowed;
use DevCoder\Exception\RouteNotFound;
use DevCoder\Route;
use DevCoder\Router;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * Class RouterTest
 * @package Test\Webbym\Routing
 */
class RouterTest extends TestCase
{
    private Router $router;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $routeHome = new Route('home_page', '/home', ['App\\Controller\\HomeController', 'home']);
        $routeArticle = new Route('article_page', '/view/article', ['App\\Controller\\HomeController', 'article']);
        $routeArticleWithParam = new Route('article_page_by_id', '/view/article/{id}', ['App\\Controller\\HomeController', 'article']);
        $routeArticleWithParams = new Route('article_page_by_id_and_page', '/view/article/{id}/{page}', ['App\\Controller\\HomeController', 'article']);

        $this->router = (new Router())
            ->add($routeHome)
            ->add($routeArticle)
            ->add($routeArticleWithParam)
            ->add($routeArticleWithParams);
    }

    public function testMatchRoute()
    {
        $route = $this->router->matchFromPath('/view/article/25', 'GET');
        $this->assertInstanceOf(Route::class, $route);

        $this->assertNotEmpty($route->getHandler());
        $this->assertNotEmpty($route->getMethods());
        $this->assertSame(['id' => '25'], $route->getAttributes());
        $this->assertInstanceOf(Route::class, $this->router->matchFromPath('/home', 'GET'));
    }

    public function testNotFoundException()
    {
        $this->expectException(RouteNotFound::class);
        $this->router->matchFromPath('/homes', 'GET');
    }

    public function testMethodNotAllowedException()
    {
        $this->expectException(MethodNotAllowed::class);
        $this->router->matchFromPath('/home', 'PUT');
    }

    public function testGenerateUrl()
    {
        $urlHome = $this->router->generateUri('home_page');
        $urlArticle = $this->router->generateUri('article_page');
        $urlArticleWithParam = $this->router->generateUri('article_page_by_id', ['id' => 25]);
        $routeArticleWithParams = $this->router->generateUri('article_page_by_id_and_page', ['id' => 25, 'page' => 3]);

        $this->assertSame($urlHome, '/home');
        $this->assertSame($urlArticle, '/view/article');
        $this->assertSame($urlArticleWithParam, '/view/article/25');
        $this->assertSame($routeArticleWithParams, '/view/article/25/3');

        $this->expectException(InvalidArgumentException::class);
        $this->router->generateUri('article_page_by_id_and_page', ['id' => 25]);
    }

    public function testGenerateAbsoluteUrl()
    {
        $urlHome = $this->router->generateUri('home_page', [], true);
        $urlArticle = $this->router->generateUri('article_page', [], true);
        $urlArticleWithParam = $this->router->generateUri('article_page_by_id', ['id' => 25], true);
        $routeArticleWithParams = $this->router->generateUri('article_page_by_id_and_page', ['id' => 25, 'page' => 3], true);

        $this->assertSame($urlHome, 'http://localhost/home');
        $this->assertSame($urlArticle, 'http://localhost/view/article');
        $this->assertSame($urlArticleWithParam, 'http://localhost/view/article/25');
        $this->assertSame($routeArticleWithParams, 'http://localhost/view/article/25/3');

        $this->expectException(InvalidArgumentException::class);
        $this->router->generateUri('article_page_by_id_and_page', ['id' => 25]);
    }
}
