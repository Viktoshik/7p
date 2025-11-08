<?php

namespace Src\Controllers\Middleware;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthMiddleware
{
    public function __construct(
        protected ResponseFactoryInterface $responseFactory
    )
    {

    }
    public function __invoke(RequestInterface $request, RequestHandlerInterface $handler)
    {
        if (!isset($_SESSION['user_id'])){
            $response = $this->responseFactory->createResponse();

            return $response->withHeader('Location', '/login')->withStatus(302);
        }
        return $handler->handle($request);
    }
}