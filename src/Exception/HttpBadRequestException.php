<?php

declare(strict_types=1);

namespace App\Exception;

use Throwable;

class HttpBadRequestException extends HttpException
{
    public function __construct(string $message = "Bad request.", ?Throwable $previous = null)
    {
        parent::__construct($message, 400, $previous);
    }
}
