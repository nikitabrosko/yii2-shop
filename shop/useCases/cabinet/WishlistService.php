<?php

namespace shop\useCases\cabinet;

use shop\entities\shop\product\Product;
use shop\entities\user\User;
use shop\exceptions\NotFoundException;

class WishlistService
{
    public function add($userId, $productId)
    {
        $user = $this->getUser($userId);
        $product = $this->getProduct($productId);

        $user->addToWishList($product->id);
        $user->save();
    }

    public function remove($userId, $productId)
    {
        $user = $this->getUser($userId);
        $product = $this->getProduct($productId);

        $user->removeFromWishList($product->id);
        $user->save();
    }

    private function getUser($id) : User
    {
        if (!$user = User::findOne(['id' => $id])) {
            throw new NotFoundException('User not found.');
        }

        return $user;
    }

    private function getProduct($id) : Product
    {
        if (!$product = Product::findOne(['id' => $id])) {
            throw new NotFoundException('Product not found.');
        }

        return $product;
    }
}