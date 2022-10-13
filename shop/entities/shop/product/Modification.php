<?php

namespace shop\entities\shop\product;

use yii\db\ActiveRecord;

/**
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property integer $price
 * @property integer $quantity
 */
class Modification extends ActiveRecord
{
    public static function create($code, $name, $price, $quantity) : self
    {
        $modification = new static();
        $modification->code = $code;
        $modification->name = $name;
        $modification->price = $price;
        $modification->quantity = $quantity;

        return $modification;
    }

    public function edit($code, $name, $price, $quantity)
    {
        $this->code = $code;
        $this->name = $name;
        $this->price = $price;
        $this->quantity = $quantity;
    }

    public function checkout($quantity): void
    {
        if ($quantity > $this->quantity) {
            throw new \DomainException('Only ' . $this->quantity . ' items are available.');
        }

        $this->quantity -= $quantity;
    }

    public function isIdEqualTo($id) : bool
    {
        return $this->id == $id;
    }

    public function isCodeEqualTo($code) : bool
    {
        return $this->code == $code;
    }

    public static function tableName() : string
    {
        return '{{%shop_modifications}}';
    }
}