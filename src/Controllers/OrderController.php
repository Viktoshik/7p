<?php

namespace Src\Controllers;

use ORM;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Views\PhpRenderer;
use Src\Services\CartService;
use YooKassa\Client;

class OrderController extends Controller
{
    public function __construct(
        PhpRenderer           $renderer,
        protected CartService $cartService,
    )
    {
        parent::__construct($renderer);
    }
    public function success(
        RequestInterface $request,
        ResponseInterface $response,
        array $args
    )
    {
        $orderId = $args['id'];
        ORM::forTable('orders')->findOne($orderId)->set([
            'status' => "success"
        ])->save();
        unset($_COOKIE['cart_id']);
        $this->cartService->getCartId();
        return $this->renderer->render($response, 'success.php');
    }

    public function store(
        RequestInterface  $request,
        ResponseInterface $response,
    )
    {
        $orderSum = 0;

        $userId = $_SESSION['user_id'];
        $cartId = $this->cartService->getCartId();
        $cartItems = $this->cartService->getCartItems();

        foreach ($cartItems as $cartItem) {
            $product = ORM::forTable('products')->findOne($cartItem['product_id']);
            $currentCartItem = ORM::forTable('cart_items')->findOne($cartItem['id'])->set([
                'price' => $product['price'],
            ]);
            $currentCartItem->save();

            $orderSum += $currentCartItem['price'] * $cartItem['count'];
        }
        $order = ORM::forTable('orders')->create([
            'user_id' => $userId,
            'cart_id' => $cartId,
        ]);
        $order->save();

        $client = new Client();
        $client->setauth('1218090', 'test_oAE61eVuKNisyXhiwzmjb8xVemFU8gQ5_9WAFDxcR_o');

        $paymentResponse = $client->createPayment(
            [
                'amount' => [
                    'value' => $orderSum,
                    'currency' => 'RUB',
                ],
                'confirmation' => [
                    'type' => 'redirect',
                    'locale' => 'ru_RU',
                    'return_url' => 'http://localhost/payment/' . $order->id,
                ],
                'capture' => true,
                'description' => 'Заказ ' . $order->id,
                'metadata' => [
                    'orderNumber' => $order->id,
                ]
            ]
        );
        $paymentId = $paymentResponse->getId();
        $paymentLink = $paymentResponse->confirmation->getConfirmationUrl();
        $status = $paymentResponse->getStatus();

        $sdf = ORM::forTable('orders')->where('cart_id', $cartId)->findOne()
            ->set([
            'order_id' => $paymentId,
            'payment_link' => $paymentLink,
            'status' => $status
        ])->save();

        ORM::forTable('carts')->findOne($cartId)->set([
            'status' =>'closed'
        ])->save();

        setcookie('cart_id', $cartId, time() + 60 * 60 * 25 * 31, "/");
        return $response->withHeader('Location', $paymentLink)->withStatus(302);
    }

    public function index(
        RequestInterface  $request,
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
            ->join('products', ['products_id', '=', 'cart_items.products_id'])
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

