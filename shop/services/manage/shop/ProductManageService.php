<?php

namespace shop\services\manage\shop;

use shop\entities\Meta;
use shop\entities\shop\Brand;
use shop\entities\shop\Category;
use shop\entities\shop\product\Product;
use shop\entities\shop\Tag;
use shop\exceptions\NotFoundException;
use shop\forms\manage\shop\product\CategoriesForm;
use shop\forms\manage\shop\product\PhotosForm;
use shop\forms\manage\shop\product\ProductCreateForm;
use shop\forms\manage\TransactionManager;

class ProductManageService
{
    private $transactionManager;

    public function __construct(TransactionManager $transactionManager)
    {
        $this->transactionManager = $transactionManager;
    }

    public function create(ProductCreateForm $form) : Product
    {
        if (!$brand = Brand::findOne(['id' => $form->brandId])) {
            throw new NotFoundException('Brand not found.');
        }

        $category = $this->getCategory($form->categories->main);

        $product = Product::create(
            $brand->id,
            $category->id,
            $form->code,
            $form->name,
            new Meta(
                $form->meta->title,
                $form->meta->description,
                $form->meta->keywords
            )
        );

        $product->setPrice($form->price->new, $form->price->old);

        foreach ($form->categories->others as $otherId) {
            $category = $this->getCategory($otherId);

            $product->assignCategory($category->id);
        }

        foreach ($form->values as $value) {
            $product->setValue($value->id, $value->value);
        }

        foreach ($form->photos->files as $file) {
            $product->addPhoto($file);
        }

        foreach ($form->tags->existing as $tagId) {
            if (!$tag = Tag::findOne(['id' => $tagId])) {
                throw new NotFoundException('Tag not found.');
            }

            $product->assignTag($tag->id);
        }

        $this->transactionManager->wrap(function() use ($form, $product) {
            foreach ($form->tags->newNames as $tagName) {
                if (!$tag = Tag::findOne(['name' => $tagName])) {
                    $tag = Tag::create($tagName, $tagName);
                    $tag->save();
                }

                $product->assignTag($tag->id);
            }
        });

        $product->save();

        return $product;
    }

    public function changeCategories($id, CategoriesForm $form)
    {
        $product = $this->getProduct($id);

        $category = $this->getCategory($form->main);

        $product->changeMainCategory($category->id);
        $product->revokeCategories();

        foreach ($form->others as $otherId) {
            $category = $this->getCategory($otherId);

            $product->assignCategory($category->id);
        }

        $product->save();
    }

    public function addPhotos($id, PhotosForm $form)
    {
        $product = $this->getProduct($id);

        foreach ($form->files as $file) {
            $product->addPhoto($file);
        }

        $product->save();
    }

    public function movePhotoUp($id, $photoId)
    {
        $product = $this->getProduct($id);

        $product->movePhotoUp($photoId);

        $product->save();
    }

    public function movePhotoDown($id, $photoId)
    {
        $product = $this->getProduct($id);

        $product->movePhotoDown($photoId);

        $product->save();
    }

    public function removePhoto($id, $photoId)
    {
        $product = $this->getProduct($id);

        $product->removePhoto($photoId);

        $product->save();
    }

    public function remove($id)
    {
        $product = $this->getProduct($id);

        $product->delete();
    }

    private function getCategory($id) : Category
    {
        if (!$category = Category::findOne(['id' => $id])) {
            throw new NotFoundException('Category not found.');
        }

        return $category;
    }

    private function getProduct($id) : Product
    {
        if (!$product = Product::findOne(['id' => $id])) {
            throw new NotFoundException('Product not found.');
        }

        return $product;
    }
}