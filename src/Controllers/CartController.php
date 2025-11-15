<?php

namespace Src\Controllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Views\PhpRenderer;
use Src\Services\CartService;

class CartController extends Controller
{
    public function __construct(
        PhpRenderer $renderer,
        protected CartService $cartService
    )
    {
        parent::__construct($renderer);
    }

    public function add(
        RequestInterface $request,
        ResponseInterface $response,

    )
    {
        $productId = $request->getParsedBody()['product_id'];
        $this->cartService->add($productId);
        return $response->withHeader('Location', '/')->withStatus(302);
    }
    public function minus(
        RequestInterface $request,
        ResponseInterface $response,
    )
    {
        $productId = $request->getParsedBody()['product_id'];
        $this->cartService->minus($productId);
        return $response->withHeader('Location', '/')->withStatus(302);
    }
}