<?php

namespace shop\cart;

use shop\entities\shop\product\Modification;
use shop\entities\shop\product\Product;

class CartItem
{
    private $product;
    private $modificationId;
    private $quantity;

    public function __construct(Product $product, $modificationId, int $quantity)
    {
        $this->product = $product;
        $this->modificationId = $modificationId;
        $this->quantity = $quantity;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getModification() : ?Modification
    {
        if ($this->modificationId) {
            return $this->product->getModification($this->modificationId);
        }

        return null;
    }

    public function getQuantity() : int
    {
        return $this->quantity;
    }

    public function getProductId() : int
    {
        return $this->product->id;
    }

    public function getModificationId()
    {
        if ($this->modificationId) {
            return $this->modificationId;
        }

        return null;
    }

    public function getId() : string
    {
        return md5(serialize([$this->product->id, $this->modificationId]));
    }

    public function changeQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    public function getPrice() : float
    {
        if ($this->modificationId) {
            return $this->product->getModification($this->modificationId)->price;
        }

        return $this->product->price_new;
    }

    public function getCost() : float
    {
        return $this->getPrice() * $this->quantity;
    }
}