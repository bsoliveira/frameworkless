<?php

declare(strict_types=1);

namespace App\Exception;

use Throwable;

class HttpNotFoundException extends HttpException
{

    public function __construct(string $message = "Not found.", ?Throwable $previous = null)
    {
        parent::__construct($message, 404, $previous);
    }
}
