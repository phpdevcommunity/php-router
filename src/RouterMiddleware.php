<?php

declare(strict_types=1);

namespace DevCoder;

use DevCoder\Exception\MethodNotAllowed;
use DevCoder\Exception\RouteNotFound;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class RouterMiddleware implements MiddlewareInterface
{
    public const ATTRIBUTE_KEY = '__route';

    private RouterInterface $router;
    private ResponseFactoryInterface $responseFactory;

    public function __construct(
        RouterInterface          $router,
        ResponseFactoryInterface $responseFactory
    )
    {
        $this->router = $router;
        $this->responseFactory = $responseFactory;
    }

    public function process(
        ServerRequestInterface  $request,
        RequestHandlerInterface $handler
    ): ResponseInterface
    {
        try {
            $route = $this->router->match($request);
            $request = $request->withAttribute(self::ATTRIBUTE_KEY, $route);
        } catch (MethodNotAllowed $exception) {
            return $this->responseFactory->createResponse(405);
        } catch (RouteNotFound $exception) {
            return $this->responseFactory->createResponse(404);
        }
        return $handler->handle($request);
    }
}
