<?php

namespace Src\Services;

use ORM;

class CartService
{
    private const COOKIE_NAME = 'cart_id';
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

    public function minus(int $productId): void{
        $cartId = $this->getCartId();
        $cart_item = ORM::forTable('cart_items')->where([
            'cart_id' => $cartId,
            'product_id' => $productId
        ])->find_one();
        if (!$cart_item) {
            return;
        }
        if ($cart_item['count'] > 1) {
            $cart_item->set([
                'count' => $cart_item['count'] - 1,
            ])->save();
        }else{
            $cart_item->delete();
        }
    }

    public function getCartItems(): array
    {
        $cartId = $this->getCartId();
        return ORM::forTable('cart_items')->where([
            'cart_id' => $cartId
        ])->findArray();
    }

    public function getGroupedItems(): array
    {
        $cartItems = $this->getCartItems();
        $result=[];
        foreach ($cartItems as $cartItem) {
            $result[$cartItem['product_id']]=$cartItem['count'];
        }
        return $result;
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