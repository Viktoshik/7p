<?php

namespace Src\Services;

use ORM;

class CartService
{
    public function add(int $productId): void
    {
    $cartId = $this->getCartId();
    $cart_item = ORM::forTable('cart_items')->where([
        'cart_id' => $cartId,
        'product_id' => $productId
    ])->find_one();
    if ($cart_item===false) {
        ORM::forTable('cart_items')->create([
            'product_id' => $productId,
            'cart_id' => $cartId,
            'count' => 1
        ])->save();
    }else{
        $cart_item->set([
            'count' => $cart_item['count'] + 1,
        ])->save();
    }

    }

    public function getCartItems(): array
    {
        $cartId = $this->getCartId();
        return ORM::forTable('cart_items')->where([
            'cart_id' => $cartId
        ])->findArray();
    }
protected function getCartId():int
    {
        if (isset($_COOKIE['cart_id'])) {
            return $_COOKIE['cart_id'];
        }
        $cart = \ORM::forTable('carts')->create();
        $cart->save();

        setcookie('cart_id', $cart->id, time() + 60 * 60 * 24 * 31);
        return $cart->id;
    }
}