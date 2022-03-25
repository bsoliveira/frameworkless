<?php

declare(strict_types=1);

namespace App\Handler;

use Throwable;
use Psr\Log\LoggerInterface;
use App\Exception\HttpException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use App\Exception\HttpMethodNotAllowedException;

class HttpErrorHandler
{
    /**
     * @var ResponseFactoryInterface
     */
    protected $responseFactory;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        LoggerInterface $logger,
        bool $showDetails
    ) {
        $this->responseFactory = $responseFactory;
        $this->logger = $logger;
        $this->showDetails = $showDetails;
    }

    /**
     * Invoke error handler
     *
     * @param Throwable $exception
     * @return ResponseInterface
     */
    public function __invoke(Throwable $exception): ResponseInterface
    {
        $response = $this->responseFactory->createResponse();

        $error = [
            'code' => 500,
            'message' => 'Internal server error',
        ];

        if ($exception instanceof HttpException) {
            $error['code'] = $exception->getCode();
            $error['message'] = $exception->getMessage();

            if ($exception instanceof HttpMethodNotAllowedException) {
                $allowedMethods = implode(', ', $exception->getAllowedMethods());
                $response = $response->withHeader('Allow', $allowedMethods);
            }
        } else {
            $this->logger->error($exception->getMessage(), $this->getErrorDetails($exception));

            if ($this->showDetails) {
                $error['exception'] = $this->getErrorDetails($exception);
            }
        }

        $response->getBody()->write(json_encode($error));

        return $response->withHeader('Content-Type', 'application/json')
            ->withStatus($error['code']);
    }

    /**
     * Returns an array with the error details.
     *
     * @param Throwable $exception
     * @return array
     */
    protected function getErrorDetails(Throwable $exception): array
    {
        $details = [];

        do {
            $details[] = $this->formatException($exception);
        } while ($exception = $exception->getPrevious());

        return $details;
    }

    /**
     * Convert exception to array.
     *
     * @param Throwable $exception
     * @return array<string|int>
     */
    protected function formatException(Throwable $exception): array
    {
        return [
            'type' => get_class($exception),
            'code' => $exception->getCode(),
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
        ];
    }
}
