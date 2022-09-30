<?php

namespace shop\readModels\shop;

use shop\entities\shop\Category;
use yii\db\ActiveRecord;

class CategoryReadRepository
{
    public function getRoot()
    {
        return Category::find()
            ->roots()
            ->one();
    }

    public function find($id)
    {
        return Category::find()
            ->andWhere(['id' => $id])
            ->andWhere(['>', 'depth', 0])
            ->one();
    }
}