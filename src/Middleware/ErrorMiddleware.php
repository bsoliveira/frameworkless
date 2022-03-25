<?php

declare(strict_types=1);

namespace App\Middleware;

use Throwable;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ErrorMiddleware implements MiddlewareInterface
{
    /**
     * @var Callable
     */
    protected $errorHandler;

    /**
     * Constructor
     *
     * @param callable $errorHandler
     * @param LoggerInterface $logger
     * @param boolean $showDetails
     */
    public function __construct(callable $errorHandler)
    {
        $this->errorHandler = $errorHandler;
    }

    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (Throwable $exception) {
            return call_user_func($this->errorHandler, $exception);
        }
    }
}
