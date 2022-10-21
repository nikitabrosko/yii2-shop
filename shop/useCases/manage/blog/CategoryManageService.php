<?php

namespace shop\useCases\manage\blog;

use shop\entities\blog\Category;
use shop\entities\Meta;
use shop\exceptions\NotFoundException;
use shop\forms\manage\blog\CategoryForm;

class CategoryManageService
{
    public function create(CategoryForm $form): Category
    {
        $category = Category::create(
            $form->name,
            $form->slug,
            $form->title,
            $form->description,
            $form->sort,
            new Meta(
                $form->meta->title,
                $form->meta->description,
                $form->meta->keywords
            )
        );

        $category->save();

        return $category;
    }

    public function edit($id, CategoryForm $form): void
    {
        $category = $this->getCategory($id);

        $category->edit(
            $form->name,
            $form->slug,
            $form->title,
            $form->description,
            $form->sort,
            new Meta(
                $form->meta->title,
                $form->meta->description,
                $form->meta->keywords
            )
        );

        $category->save();
    }

    public function remove($id): void
    {
        $category = $this->getCategory($id);

        $category->delete();
    }

    private function getCategory($id) : Category
    {
        if (!$category = Category::findOne($id)) {
            throw new NotFoundException('Category not found.');
        }

        return $category;
    }
}