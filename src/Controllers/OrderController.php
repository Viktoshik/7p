<?php

namespace Src\Controllers;

use ORM;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Views\PhpRenderer;
use Src\Services\CartService;

class OrderController extends Controller
{
    public function __construct(
        PhpRenderer $renderer,
        protected CartService $cartService,
    )
    {
       parent::__construct($renderer);
    }
    public function store(
        RequestInterface $request,
        ResponseInterface $response,
    )
    {
        ORM::forTable('orders')
        ->create([
            'user_id' => $_SESSION['user_id'],
            'cart_id' => $this->cartService->getCartId(),
        ])->save();
        $cartItems = $this->cartService->getCartItems();
        foreach ($cartItems as $cartItem) {
            ORM::forTable('cartItems')
                ->findOne($cartItem['id'])
                ->set('price', $cartItem['product_price'])
                ->save();
        }
            ORM::forTable('carts')
                ->findOne($this->cartService->getCartId())
                ->set('status', 'closed')
                ->save();
            return $response
                ->withStatus(302)
                ->withHeader('Location', '/orders');
    }
    public function index(
        RequestInterface $request,
        ResponseInterface $response,
    )
    {
        $orders = ORM::forTable('orders')
            ->where('user_id', $_SESSION['user_id'])
            ->orderByDesc('id')
            ->findArray();

        $cartIds = array_column($orders, 'cart_id');
        $cartItems = ORM::forTable('order_items')
            ->select('cart_items.*')
            ->select('product_name', 'product_name')
            ->join('products',
            ['products_id', '=', 'cart_items.products_id'])
            ->whereIn('cart_id', $cartIds)
            ->findArray();

        $orderItemsGrouped = [];

        foreach ($cartItems as $cartItem) {
            $orderItemsGrouped[$cartItem['cart_id']][] = $cartItem;
        }
        return $this->renderer->render($response, 'orders.php', [
            'orders' => $orders,
            'orderItemsGrouped' => $orderItemsGrouped,
        ]);
    }
}

