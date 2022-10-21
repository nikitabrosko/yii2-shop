<?php

namespace shop\readModels\shop;

use shop\entities\shop\Category;
use shop\readModels\shop\views\CategoryView;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class CategoryReadRepository
{
    public function getRoot() : ?Category
    {
        return Category::find()
            ->roots()
            ->one();
    }

    public function getAll() : array
    {
        return Category::find()
            ->andWhere(['>', 'depth', 0])
            ->orderBy('lft')
            ->all();
    }

    public function find($id) : ?Category
    {
        return Category::find()
            ->andWhere(['id' => $id])
            ->andWhere(['>', 'depth', 0])
            ->one();
    }

    public function findBySlug($slug) : ?Category
    {
        return Category::find()
            ->andWhere(['slug' => $slug])
            ->andWhere(['>', 'depth', 0])
            ->one();
    }

    public function getTreeWithSubsOf(Category $category = null): array
    {
        $query = Category::find()
            ->andWhere(['>', 'depth', 0])
            ->orderBy('lft');

        if ($category) {
            $criteria = ['or', ['depth' => 1]];

            foreach (ArrayHelper::merge([$category], $category->parents) as $item) {
                $criteria[] = ['and', ['>', 'lft', $item->lft], ['<', 'rgt', $item->rgt], ['depth' => $item->depth + 1]];
            }

            $query->andWhere($criteria);
        } else {
            $query->andWhere(['depth' => 1]);
        }

        return array_map(function (Category $category) {
            return new CategoryView($category, 0);
        }, $query->all());
    }
}