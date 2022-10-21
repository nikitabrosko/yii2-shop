<?php

namespace shop\entities\shop;

use shop\entities\shop\queries\DeliveryMethodQuery;
use shop\exceptions\DeleteErrorException;
use shop\exceptions\SavingErrorException;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string $name
 * @property int $cost
 * @property int $min_weight
 * @property int $max_weight
 * @property int $sort
 */
class DeliveryMethod extends ActiveRecord
{
    public static function create($name, $cost, $minWeight, $maxWeight, $sort) : self
    {
        $method = new self();
        $method->name = $name;
        $method->cost = $cost;
        $method->min_weight = $minWeight;
        $method->max_weight = $maxWeight;
        $method->sort = $sort;

        return $method;
    }

    public function edit($name, $cost, $minWeight, $maxWeight, $sort)
    {
        $this->name = $name;
        $this->cost = $cost;
        $this->min_weight = $minWeight;
        $this->max_weight = $maxWeight;
        $this->sort = $sort;
    }

    public function isAvailableForWeight($weight): bool
    {
        return (!$this->min_weight || $this->min_weight <= $weight) && (!$this->max_weight || $weight <= $this->max_weight);
    }

    public static function tableName() : string
    {
        return '{{%shop_delivery_methods}}';
    }

    public static function find() : DeliveryMethodQuery
    {
        return new DeliveryMethodQuery(self::class);
    }

    public function save($runValidation = true, $attributeNames = null) : bool
    {
        if (!parent::save($runValidation, $attributeNames)) {
            throw new SavingErrorException('DeliveryMethod saving error.');
        }

        return true;
    }

    public function delete() : bool
    {
        if (!parent::delete()) {
            throw new DeleteErrorException('DeliveryMethod deleting error.');
        }

        return true;
    }
}