<?php

declare(strict_types=1);

namespace App\Exception;

use Throwable;

class HttpInternalServerErrorException extends HttpException
{

    public function __construct(string $message = "Internal server error.", ?Throwable $previous = null)
    {
        parent::__construct($message, 500, $previous);
    }
}
