<?php

namespace Src\Controllers\Middleware;

use ORM;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AdminMiddleware
{
    public function __construct(
        protected ResponseFactoryInterface $responseFactory
    )
    {

    }
    public function __invoke(RequestInterface $request, RequestHandlerInterface $handler)
    {
        $user = ORM::forTable('users')->findOne($_SESSION['user_id']);
        if (!$user['admin']){
            $response = $this->responseFactory->createResponse();

            return $response->withHeader('Location', '/login')->withStatus(302);
        }
        return $handler->handle($request);
    }
}