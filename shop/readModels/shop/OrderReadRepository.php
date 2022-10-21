<?php

namespace shop\readModels\shop;

use shop\entities\shop\order\Order;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;

class OrderReadRepository
{
    public function getOwn($userId) : ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query' => Order::find()
                ->andWhere(['user_id' => $userId])
                ->orderBy(['id' => SORT_DESC]),
            'sort' => false,
        ]);
    }

    public function findOwn($userId, $id)
    {
        return Order::find()
            ->andWhere(['user_id' => $userId, 'id' => $id])
            ->one();
    }
}