<?php

declare(strict_types=1);

namespace App;

use Relay\Relay;
use App\Middleware\RoutingMiddleware;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;
use Narrowspark\HttpEmitter\SapiEmitter;
use Psr\Http\Server\MiddlewareInterface;
use function Fastroute\cachedDispatcher;

class App
{
    /**
     * @var ContainerInterface
     */
    protected $containter;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var array
     */
    protected $middlewares = [];

    /**
     * Constructor
     *
     * @param ContainerInterface $containter
     * @param RequestInterface $request
     */
    public function __construct(ContainerInterface $containter, RequestInterface $request)
    {
        $this->request = $request;
        $this->containter = $containter;
    }

    /**
     * Get the containter
     *
     * @return ContainerInterface
     */
    public function getContainter(): ContainerInterface
    {
        return $this->containter;
    }

    /**
     * Get the request
     *
     * @return RequestInterface
     */
    public function getRequest(): RequestInterface
    {
        return $this->request;
    }

    /**
     *  Add middleware to queue.
     *
     * @param MiddlewareInterface $middleware
     * @return void
     */
    public function add(MiddlewareInterface $middleware)
    {
        $this->middlewares[] = $middleware;
    }

    /**
     * Enable Fast Router middleware.
     *
     * @param callable $routeDefinitionCallback
     * @param string $cacheFile
     * @return void
     */
    public function addRoutingMiddleware(callable $routeDefinitionCallback, string $cacheFile = '')
    {
        $routes = cachedDispatcher($routeDefinitionCallback, [
            'cacheFile' => $cacheFile,
            'cacheDisabled' => empty($cacheFile),
        ]);

        $this->add(new RoutingMiddleware($routes, $this->getContainter()));
    }

    /**
     * Run App
     *
     * @return void
     */
    public function run(): void
    {
        $relay = new Relay($this->middlewares);
        $response = $relay->handle($this->getRequest());

        $emitter = new SapiEmitter();
        $emitter->emit($response);
    }
}
