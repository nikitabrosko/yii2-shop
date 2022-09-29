<?php

namespace shop\services\manage\shop;

use shop\entities\Meta;
use shop\entities\shop\Brand;
use shop\entities\shop\Category;
use shop\entities\shop\product\Product;
use shop\entities\shop\Tag;
use shop\exceptions\NotFoundException;
use shop\forms\manage\shop\product\CategoriesForm;
use shop\forms\manage\shop\product\ModificationForm;
use shop\forms\manage\shop\product\PhotosForm;
use shop\forms\manage\shop\product\ProductCreateForm;
use shop\forms\manage\shop\product\ProductEditForm;
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
        $brand = $this->getBrand($form->brandId);

        $category = $this->getCategory($form->categories->main);

        $product = Product::create(
            $brand->id,
            $category->id,
            $form->code,
            $form->name,
            $form->description,
            new Meta(
                $form->meta->title,
                $form->meta->description,
                $form->meta->keywords
            )
        );

        $product->updatePrice($form->price->new, $form->price->old);

        foreach ($form->categories->others as $otherId) {
            $category = $this->getCategory($otherId);

            $product->assignCategory($category->id);
        }

        foreach ($form->values as $value) {
            $product->updateValue($value->id, $value->value);
        }

        foreach ($form->photos->files as $file) {
            $product->addPhoto($file);
        }

        $this->assignTags($form, $product);

        $product->save();

        return $product;
    }

    public function edit($id, ProductEditForm $form)
    {
        $product = $this->getProduct($id);
        $brand = $this->getBrand($form->brandId);

        $product->edit(
            $brand->id,
            $form->code,
            $form->name,
            $form->description,
            new Meta(
                $form->meta->title,
                $form->meta->description,
                $form->meta->keywords
            )
        );

        foreach ($form->values as $value) {
            $product->updateValue($value->id, $value->value);
        }

        $product->revokeTags();
        $this->assignTags($form, $product);

        $product->save();
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

    public function addRelatedProduct($id, $otherId): void
    {
        $product = $this->getProduct($id);
        $other = $this->getProduct($otherId);
        $product->assignRelatedProduct($other->id);

        $product->save();
    }

    public function removeRelatedProduct($id, $otherId): void
    {
        $product = $this->getProduct($id);
        $other = $this->getProduct($otherId);
        $product->revokeRelatedProduct($other->id);

        $product->save();
    }

    public function addModification($id, ModificationForm $form): void
    {
        $product = $this->getProduct($id);

        $product->addModification(
            $form->code,
            $form->name,
            $form->price
        );

        $product->save();
    }

    public function editModification($id, $modificationId, ModificationForm $form): void
    {
        $product = $this->getProduct($id);

        $product->editModification(
            $modificationId,
            $form->code,
            $form->name,
            $form->price
        );

        $product->save();
    }

    public function removeModification($id, $modificationId): void
    {
        $product = $this->getProduct($id);

        $product->removeModification($modificationId);

        $product->save();
    }

    public function remove($id)
    {
        $product = $this->getProduct($id);

        $product->delete();
    }

    private function assignTags($form, $product)
    {
        foreach ($form->tags->existing as $tagId) {
            $tag = $this->getTag($tagId);
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

    private function getBrand($id) : Brand
    {
        if (!$brand = Brand::findOne(['id' => $id])) {
            throw new NotFoundException('Brand not found.');
        }

        return $brand;
    }

    private function getTag($id) : Tag
    {
        if (!$tag = Tag::findOne(['id' => $id])) {
            throw new NotFoundException('Tag not found.');
        }

        return $tag;
    }
}