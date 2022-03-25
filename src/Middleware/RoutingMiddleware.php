<?php

declare(strict_types=1);

namespace App\Middleware;

use RuntimeException;
use FastRoute\Dispatcher;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use App\Exception\HttpNotFoundException;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use App\Exception\HttpMethodNotAllowedException;

class RoutingMiddleware implements MiddlewareInterface
{
    /**
     * @var Dispatcher
     */
    protected $router;

    /**
     * @var ContainerInterface Used to resolve the handlers
     */
    protected $container;

    /**
     * Constructor
     *
     * @param Dispatcher $router
     * @param ContainerInterface $container
     */
    public function __construct(Dispatcher $router, ContainerInterface $container)
    {
        $this->router = $router;
        $this->container = $container;
    }

    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $route = $this->router->dispatch($request->getMethod(), rawurldecode($request->getUri()->getPath()));

        if ($route[0] === Dispatcher::NOT_FOUND) {
            throw new HttpNotFoundException();
        }

        if ($route[0] === Dispatcher::METHOD_NOT_ALLOWED) {
            throw new HttpMethodNotAllowedException($route[1]);
        }

        foreach ($route[2] as $name => $value) {
            $request = $request->withAttribute($name, $value);
        }

        $requestHandler = $this->container->get($route[1]);

        if ($requestHandler instanceof RequestHandlerInterface) {
            return $requestHandler->handle($request);
        }

        throw new RuntimeException(sprintf('Invalid request handler: %s', gettype($requestHandler)));
    }
}
