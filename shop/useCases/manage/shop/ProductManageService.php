<?php

namespace shop\useCases\manage\shop;

use shop\entities\Meta;
use shop\entities\shop\Brand;
use shop\entities\shop\Category;
use shop\entities\shop\product\Product;
use shop\entities\shop\product\TagAssignment;
use shop\entities\shop\Tag;
use shop\exceptions\NotFoundException;
use shop\forms\manage\shop\product\CategoriesForm;
use shop\forms\manage\shop\product\ModificationForm;
use shop\forms\manage\shop\product\PhotosForm;
use shop\forms\manage\shop\product\PriceForm;
use shop\forms\manage\shop\product\ProductCreateForm;
use shop\forms\manage\shop\product\ProductEditForm;
use shop\forms\manage\shop\product\QuantityForm;
use shop\services\TransactionManager;

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
        $this->assertIsNotRoot($category);

        $product = Product::create(
            $brand->id,
            $category->id,
            $form->code,
            $form->name,
            $form->description,
            $form->weight,
            $form->quantity->quantity,
            new Meta(
                $form->meta->title,
                $form->meta->description,
                $form->meta->keywords
            )
        );

        $product->updatePrice($form->price->new, $form->price->old);

        if ($form->categories->others) {
            foreach ($form->categories->others as $otherId) {
                $category = $this->getCategory($otherId);

                $product->assignCategory($category->id);
            }
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
        $category = $this->getCategory($form->categories->main);
        $this->assertIsNotRoot($category);

        $product->edit(
            $brand->id,
            $form->code,
            $form->name,
            $form->description,
            $form->weight,
            new Meta(
                $form->meta->title,
                $form->meta->description,
                $form->meta->keywords
            )
        );

        $product->revokeCategories();
        $this->changeCategories($product->id, $form->categories);

        foreach ($form->values as $value) {
            $product->updateValue($value->id, $value->value);
        }

        $product->revokeTags();
        $this->assignTags($form, $product);

        $product->save();
    }

    public function changePrice($id, PriceForm $form): void
    {
        $product = $this->getProduct($id);

        $product->updatePrice($form->new, $form->old);

        $product->save($product);
    }

    public function changeQuantity($id, QuantityForm $form): void
    {
        $product = $this->getProduct($id);
        $product->setQuantity($form->quantity);

        $product->save();
    }


    public function activate($id)
    {
        $product = $this->getProduct($id);
        $product->activate();

        $product->save();
    }

    public function draft($id)
    {
        $product = $this->getProduct($id);
        $product->draft();

        $product->save();
    }

    public function changeCategories($id, CategoriesForm $form)
    {
        $product = $this->getProduct($id);

        $category = $this->getCategory($form->main);
        $this->assertIsNotRoot($category);

        $product->changeMainCategory($category->id);
        $product->revokeCategories();

        if ($form->others) {
            foreach ($form->others as $otherId) {
                $category = $this->getCategory($otherId);
                $this->assertIsNotRoot($category);

                $product->assignCategory($category->id);
            }
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
            $form->price,
            $form->quantity
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
            $form->price,
            $form->quantity
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
        $this->transactionManager->wrap(function() use ($form, $product) {
            if ($form->tags->existing) {
                foreach ($form->tags->existing as $tagId) {
                    $product->assignTag($tagId);
                }
            }

            if ($form->tags->newNames) {
                foreach ($form->tags->newNames as $tagName) {
                    if (!Tag::findOne(['name' => $tagName]) && !empty($tagName)) {
                        $tag = Tag::create($tagName, $tagName);
                        $tag->save();

                        $product->assignTag($tag->id);
                    }
                }
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

    private function assertIsNotRoot(?Category $category)
    {
        if ($category->isRoot()) {
            throw new \DomainException('Unable to manage the root category.');
        }
    }
}