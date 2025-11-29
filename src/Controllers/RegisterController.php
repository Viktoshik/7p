<?php

namespace Src\Controllers;

use ORM;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Views\PhpRenderer;
use Src\Services\CartService;

class RegisterController extends Controller
{
    public function __construct(
        PhpRenderer $renderer,
        protected CartService $cartService
    )
    {
        parent::__construct($renderer);
    }
    public function setLayout():void
    {

    }
    public function index(
        RequestInterface $request,
        ResponseInterface $response,
    )
    {
        return $this->renderer->render($response, '/auth/register.php');
    }
    public function register(
        RequestInterface $request,
        ResponseInterface $response,
    )
    {
        ORM::forTable('users')->create([
            'phone' => $request->getParsedBody()['phone'],
            'password' => $request->getParsedBody()['password'],
        ])->save();
        $cartId= $this->cartService->getCartId();
        return $response->withHeader('Location', '/login')->withStatus(302);
    }
}