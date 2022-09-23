<?php

namespace shop\forms\manage\shop\product;

use yii\base\Model;
use shop\entities\shop\product\Product;
use yii\helpers\ArrayHelper;

class CategoriesForm extends Model
{
    public $main;
    public $others = [];

    public function __construct(Product $product = null, $config = [])
    {
        if ($product) {
            $this->main = $product->category_id;
            $this->others = ArrayHelper::getColumn($product->categoryAssignment, 'category_id');
        }

        parent::__construct($config);
    }

    public function rules() : array
    {
        return [
            ['main', 'required'],
            ['main', 'integer'],
            ['others', 'each', 'rule' => ['integer']],
        ];
    }
}