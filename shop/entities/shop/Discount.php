<?php

namespace shop\entities\shop;

use shop\entities\shop\queries\DiscountQuery;
use shop\exceptions\DeleteErrorException;
use shop\exceptions\SavingErrorException;
use yii\db\ActiveRecord;

/**
 * @property integer $id
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
        $this->sort = $sort;
    }

    public function activate()
    {
        if ($this->active) {
            throw new \DomainException('Discount is already active.');
        }

        $this->active = true;
    }

    public function draft()
    {
        if (!$this->active) {
            throw new \DomainException('Discount is already draft.');
        }

        $this->active = false;
    }

    public function isEnabled() : bool
    {
        return true;
    }

    public static function tableName() : string
    {
        return '{{%shop_discounts}}';
    }

    public static function find() : DiscountQuery
    {
        return new DiscountQuery(static::class);
    }

    public function save($runValidation = true, $attributeNames = null) : bool
    {
        if (!parent::save($runValidation, $attributeNames)) {
            throw new SavingErrorException('Discount saving error.');
        }

        return true;
    }

    public function delete() : bool
    {
        if (!parent::delete()) {
            throw new DeleteErrorException('Discount deleting error.');
        }

        return true;
    }
}