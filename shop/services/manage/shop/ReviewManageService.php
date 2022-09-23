<?php

namespace shop\services\manage\shop;

use shop\entities\shop\product\Product;
use shop\exceptions\NotFoundException;
use shop\forms\manage\shop\product\ReviewEditForm;

class ReviewManageService
{
    public function edit($id, $reviewId, ReviewEditForm $form): void
    {
        $product = $this->getProduct($id);

        $product->editReview(
            $reviewId,
            $form->vote,
            $form->text
        );

        $product->save();
    }

    public function activate($id, $reviewId): void
    {
        $product = $this->getProduct($id);
        $product->activateReview($reviewId);

        $product->save();
    }

    public function draft($id, $reviewId): void
    {
        $product = $this->getProduct($id);
        $product->draftReview($reviewId);

        $product->save();
    }

    public function remove($id, $reviewId): void
    {
        $product = $this->getProduct($id);
        $product->removeReview($reviewId);

        $product->save();
    }

    private function getProduct($id) : Product
    {
        if (!$product = Product::findOne(['id' => $id])) {
            throw new NotFoundException('Product not found.');
        }

        return $product;
    }
}