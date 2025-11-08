<?php

namespace Src\Controllers;

use DI\Container;
use http\Message;
use ORM;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Views\PhpRenderer;
use Src\Services\CartService;

class ProductController extends Controller
{
    public function __construct(PhpRenderer $renderer, private CartService $cartService)
    {
        parent::__construct($renderer);
    }

    public function index(
        RequestInterface $request,
        ResponseInterface $response,
    )
    {
        $products = ORM::forTable('products')->findMany();
        $cartItems = $this->cartService->getCartItems();
        return $this->renderer->render($response, 'product/index.php', [
            'products' => $products,
            'cartItems' => $cartItems,
        ]);
    }
}