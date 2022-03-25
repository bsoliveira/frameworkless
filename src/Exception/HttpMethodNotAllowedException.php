<?php

declare(strict_types=1);


namespace App\Exception;

use Throwable;

class HttpMethodNotAllowedException extends HttpException
{

    /**
     * @var string[]
     */
    protected $allowedMethods = [];

    public function __construct(array $methods, string $message = "Method not allowed.", ?Throwable $previous = null)
    {
        $this->setAllowedMethods($methods);

        parent::__construct($message, 405, $previous);
    }

    /**
     * @param string[] $methods
     * @return self
     */
    public function setAllowedMethods(array $methods): HttpMethodNotAllowedException
    {
        $this->allowedMethods = $methods;
        $this->message = 'Method not allowed. Must be one of: ' . implode(', ', $methods);
        return $this;
    }

    /**
     * @return string[]
     */
    public function getAllowedMethods(): array
    {
        return $this->allowedMethods;
    }
}
