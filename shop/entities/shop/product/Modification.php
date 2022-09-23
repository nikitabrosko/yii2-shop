<?php

namespace shop\entities\shop\product;

use yii\db\ActiveRecord;

/**
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property integer $price
 */
class Modification extends ActiveRecord
{
    public static function create($code, $name, $price) : self
    {
        $modification = new static();
        $modification->code = $code;
        $modification->name = $name;
        $modification->price = $price;

        return $modification;
    }

    public function edit($code = null, $name = null, $price = null)
    {
        $this->code = $code;
        $this->name = $name;
        $this->price = $price;
    }

    public function isIdEqualTo($id) : bool
    {
        return $this->id === $id;
    }

    public function isCodeEqualTo($code) : bool
    {
        return $this->code === $code;
    }

    public static function tableName() : string
    {
        return '{{%shop_modification}}';
    }
}