<?php

namespace shop\entities\shop;

use shop\entities\shop\queries\DiscountQuery;
use yii\db\ActiveRecord;

/**
 * @property integer $percent
 * @property string $name
 * @property integer $from_date
 * @property integer $to_date
 * @property integer $sort
 * @property bool $active
 */
class Discount extends ActiveRecord
{
    public static function create($percent, $name, $fromDate, $toDate, $sort) : self
    {
        $discount = new self();
        $discount->percent = $percent;
        $discount->name = $name;
        $discount->from_date = $fromDate;
        $discount->to_date = $toDate;
        $discount->sort = $sort;
        $discount->active = false;

        return $discount;
    }

    public function edit($percent, $name, $fromDate, $toDate, $sort)
    {
        $this->percent = $percent;
        $this->name = $name;
        $this->from_date = $fromDate;
        $this->to_date = $toDate;
    }

    public function activate()
    {
        $this->active = true;
    }

    public function draft()
    {
        $this->active = false;
    }

    public static function tableName() : string
    {
        return '{{%shop_discounts}}';
    }

    public static function find() : DiscountQuery
    {
        return new DiscountQuery(static::class);
    }
}