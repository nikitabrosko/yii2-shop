<?php

namespace shop\entities\shop\product;

use yii\db\ActiveRecord;

/**
 * @property integer $product_id
 * @property integer $related_product_id
 */
class RelatedAssignment extends ActiveRecord
{
    public static function create($related_product_id) : self
    {
        $assignment = new static();
        $assignment->related_product_id = $related_product_id;

        return $assignment;
    }

    public function isForProduct($id) : bool
    {
        return $this->related_product_id == $id;
    }

    public static function tableName() : string
    {
        return '{{%shop_related_products_assignments}}';
    }
}