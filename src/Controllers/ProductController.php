<?php

namespace Src\Controllers;

use DI\Container;
use ORM;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Views\PhpRenderer;

class ProductController extends Controller
{
    public function __construct(PhpRenderer $renderer)
    {
        parent::__construct($renderer, $flash);
    }

    public function index(
        RequestInterface $request,
        ResponseInterface $response,
    )
    {
        $products = ORM::forTable('products')->findMany();
        return $this->renderer->render($response, 'product/index.php', [
            'products' => $products
        ]);
    }
    public function getCartItems(): array
    {
     $cartId = $this->getCartId();
     return ORM::forTable('cart_items')->where('')
    }
}