<?php

namespace shop\entities\blog\post\queries;

use shop\entities\blog\post\Post;
use yii\db\ActiveQuery;

class PostQuery extends ActiveQuery
{
    public function active($alias = null)
    {
        return $this->andWhere([
            ($alias ? $alias . '.' : '') . 'status' => Post::STATUS_ACTIVE,
        ]);
    }
}