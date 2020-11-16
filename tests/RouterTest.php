<?php
/**
 * Created by PhpStorm.
 * User: fadymichel
 * Date: 20/05/18
 * Time: 01:57
 */

namespace Test\DevCoder;

use PHPUnit\Framework\TestCase;
use DevCoder\Route;
use DevCoder\Router;

/**
 * Class RouterTest
 * @package Test\Webbym\Routing
 */
class RouterTest extends TestCase
{

    /**
     * @var Router
     */
    private $router;

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


    public function testGenerateUrl() {

        $urlHome = $this->router->generateUri('home_page');
        $urlArticle = $this->router->generateUri('article_page');
        $urlArticleWithParam = $this->router->generateUri('article_page_by_id', ['id' => 25]);
        $routeArticleWithParams = $this->router->generateUri('article_page_by_id_and_page', ['id' => 25, 'page' => 3]);

        $this->assertEquals($urlHome, '/home');
        $this->assertEquals($urlArticle, '/view/article');
        $this->assertEquals($urlArticleWithParam, '/view/article/25');
        $this->assertEquals($routeArticleWithParams, '/view/article/25/3');

    }

    public function testResolveRoute() {



    }

}