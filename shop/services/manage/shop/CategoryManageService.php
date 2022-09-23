<?php

namespace shop\services\manage\shop;

use shop\entities\Meta;
use shop\entities\shop\Category;
use shop\forms\manage\shop\CategoryForm;
use yii\helpers\Inflector;

class CategoryManageService
{
    public function create(CategoryForm $form) : Category
    {
        $parent = Category::findOne(['id' => $form->parentId]);

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
        $category = Category::findOne(['id' => $id]);

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
            $parent = Category::findOne(['id' => $form->parentId]);

            $category->appendTo($parent);
        }

        $category->save();
    }

    public function remove($id)
    {
        $category = Category::findOne(['id' => $id]);

        $this->assertIsNotRoot($category);

        $category->delete();
    }

    private function assertIsNotRoot(?Category $category)
    {
        if ($category->isRoot()) {
            throw new \DomainException('Unable to manage the root category.');
        }
    }
}