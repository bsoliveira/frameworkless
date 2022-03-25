<?php

declare(strict_types=1);

namespace App\Exception;

use Throwable;

class HttpForbiddenException extends HttpException
{

    public function __construct(string $message = "Forbidden.", ?Throwable $previous = null)
    {
        parent::__construct($message, 403, $previous);
    }
}
