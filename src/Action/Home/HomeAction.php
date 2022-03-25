<?php

declare(strict_types=1);

namespace App\Action\Home;

use App\Action\ActionBase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class HomeAction extends ActionBase implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $data = [
            'message' => 'Hello World!',
        ];

        return $this->respond($data);
    }
}
