<?php
/**
 * Created by PhpStorm.
 * User: fadymichel
 * Date: 20/05/18
 * Time: 01:57
 */

namespace Test\Webbym\Routing;


use PHPUnit\Framework\TestCase;
use Webbym\Routing\Route;
use Webbym\Routing\Router;

/**
 * Class RouterTest
 * @package Test\Webbym\Routing
 */
class RouterTest extends TestCase
{


    public function testGenerateUrl() {

        $routeHome = new Route('home_page', '/home', 'App\\Controller\\HomeController', 'home');
        $routeArticle = new Route('article_page', '/view/article', 'App\\Controller\\HomeController', 'article');
        $routeArticleWithParam = new Route('article_page_by_id', '/view/article/{id}', 'App\\Controller\\HomeController', 'article');
        $routeArticleWithParams = new Route('article_page_by_id_and_page', '/view/article/{id}/{page}', 'App\\Controller\\HomeController', 'article');

        $router = (new Router())
            ->addRoute($routeHome)
            ->addRoute($routeArticle)
            ->addRoute($routeArticleWithParam)
            ->addRoute($routeArticleWithParams);

        $urlHome = $router->generateUri('home_page');
        $urlArticle = $router->generateUri('article_page');
        $urlArticleWithParam = $router->generateUri('article_page_by_id', ['id' => 25]);
        $routeArticleWithParams = $router->generateUri('article_page_by_id_and_page', ['id' => 25, 'page' => 3]);

        $this->assertEquals($urlHome, '/home');
        $this->assertEquals($urlArticle, '/view/article');
        $this->assertEquals($urlArticleWithParam, '/view/article/25');
        $this->assertEquals($routeArticleWithParams, '/view/article/25/3');


    }

}