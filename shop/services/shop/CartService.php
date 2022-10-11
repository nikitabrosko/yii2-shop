<?php

namespace shop\services\shop;

use shop\cart\Cart;
use shop\cart\CartItem;
use shop\entities\shop\product\Product;
use shop\exceptions\NotFoundException;

class CartService
{
    private $cart;

    public function __construct(Cart $cart)
    {
        $this->cart = $cart;
    }

    public function getCart() : Cart
    {
        return $this->cart;
    }

    public function add($productId, $modificationId, $quantity): void
    {
        if (!$product = Product::findOne(['id' => $productId])) {
            throw new NotFoundException('Product not found.');
        }

        $modId = $modificationId ? $product->getModification($modificationId)->id : null;

        $this->cart->add(new CartItem($product, $modId, $quantity));
    }

    public function set($id, $quantity)
    {
        $this->cart->set($id, $quantity);
    }

    public function remove($id)
    {
        $this->cart->remove($id);
    }

    public function clear()
    {
        $this->cart->clear();
    }
}