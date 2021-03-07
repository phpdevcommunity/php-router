<?php

namespace DevCoder;

use DevCoder\Exception\RouteNotFound;
use Exception;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class RouterMiddleware
 * @package App\Middleware
 */
final class RouterMiddleware implements MiddlewareInterface
{
    const CONTROLLER = '_controller';
    const ACTION = '_action';
    const NAME = '_name';

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    public function __construct(RouterInterface $router, ResponseFactoryInterface $responseFactory)
    {
        $this->router = $router;
        $this->responseFactory = $responseFactory;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            $route = $this->router->match($request);
            $controller = $route->getParameters();
            $attributes = array_merge([
                static::CONTROLLER => $controller[0],
                static::ACTION => $controller[1] ?? null,
                static::NAME => $route->getName(),
            ], $route->getVars());

            foreach ($attributes as $key => $value) {
                $request = $request->withAttribute($key, $value);
            }

        } catch (RouteNotFound $e) {
            return $this->responseFactory->createResponse(404);
        } catch (\Throwable $e) {
            throw $e;
        }

        return $handler->handle($request);
    }
}
