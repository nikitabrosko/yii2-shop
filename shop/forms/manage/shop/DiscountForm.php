<?php

namespace shop\forms\manage\shop;

use shop\entities\shop\Discount;
use shop\helpers\DateConverterHelper;
use yii\base\Model;

class DiscountForm extends Model
{
    public $percent;
    public $name;
    public $from_date;
    public $to_date;
    public $sort;

    public function __construct(Discount $discount = null, $config = [])
    {
        if ($discount) {
            $this->percent = $discount->percent;
            $this->name = $discount->name;
            $this->from_date = DateConverterHelper::convertTimestampToDate('d.m.Y', $discount->from_date);
            $this->to_date = DateConverterHelper::convertTimestampToDate('d.m.Y', $discount->to_date);
            $this->sort = $discount->sort;
        }

        parent::__construct($config);
    }

    public function rules() : array
    {
        return [
            [['percent', 'sort'], 'integer'],
            ['name', 'string', 'max' => 255],
            [['from_date', 'to_date'], 'date', 'format' => 'dd.mm.yyyy'],
        ];
    }
}