<?php

namespace shop\services\manage\shop;

use shop\entities\Meta;
use shop\entities\shop\Category;
use shop\entities\shop\product\Product;
use shop\exceptions\NotFoundException;
use shop\forms\manage\shop\CategoryForm;
use yii\helpers\Inflector;

class CategoryManageService
{
    public function create(CategoryForm $form) : Category
    {
        $parent = $this->getCategory($form->parentId);

        $category = Category::create(
            $form->name,
            $form->slug ?: Inflector::slug($form->name),
            $form->title,
            $form->description,
            new Meta(
                $form->meta->title,
                $form->meta->description,
                $form->meta->keywords
            )
        );

        $category->appendTo($parent);
        $category->save();

        return $category;
    }

    public function edit($id, CategoryForm $form)
    {
        $category = $this->getCategory($id);

        $this->assertIsNotRoot($category);

        $category->edit(
            $form->name,
            $form->slug,
            $form->title,
            $form->description,
            new Meta(
                $form->meta->title,
                $form->meta->description,
                $form->meta->keywords
            )
        );

        if ($form->parentId !== $category->parent->id) {
            $parent = $this->getCategory($form->parentId);

            $category->appendTo($parent);
        }

        $category->save();
    }

    public function moveUp($id)
    {
        $category = $this->getCategory($id);

        $this->assertIsNotRoot($category);

        if ($prev = $category->prev) {
            $category->insertBefore($prev);
        }

        $category->save();
    }

    public function moveDown($id)
    {
        $category = $this->getCategory($id);

        $this->assertIsNotRoot($category);

        if ($next = $category->next) {
            $category->insertAfter($next);
        }

        $category->save();
    }

    public function remove($id)
    {
        $category = $this->getCategory($id);

        $this->assertIsNotRoot($category);

        if (Product::find()->andWhere(['category_id' => $id])->exists()) {
            throw new \DomainException('Unable to delete category with products.');
        }

        $category->delete();
    }

    private function assertIsNotRoot(?Category $category)
    {
        if ($category->isRoot()) {
            throw new \DomainException('Unable to manage the root category.');
        }
    }

    private function getCategory($id) : Category
    {
        if (!$category = Category::findOne(['id' => $id])) {
            throw new NotFoundException('Category not found.');
        }

        return $category;
    }
}