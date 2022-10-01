<?php

namespace shop\entities\shop;

use shop\entities\behaviors\MetaBehavior;
use shop\entities\Meta;
use yii\db\ActiveRecord;

/**
 * @property integer $id
 * @property string $name
 * @property string $slug
 * @property Meta $meta
 */
class Brand extends ActiveRecord
{
    public $meta;

    public static function create(string $name, string $slug, Meta $meta) : self
    {
        $brand = new static();
        $brand->name = $name;
        $brand->slug = $slug;
        $brand->meta = $meta;

        return $brand;
    }

    public function edit(string $name = null, string $slug = null, Meta $meta = null)
    {
        if ($name) $this->name = $name;
        if ($slug) $this->slug = $slug;
        if ($meta) $this->meta = $meta;
    }

    public static function tableName() : string
    {
        return '{{%shop_brands}}';
    }

    public function save($runValidation = true, $attributeNames = null) : bool
    {
        if (!parent::save($runValidation, $attributeNames)) {
            throw new \DomainException('Brand saving error.');
        }

        return true;
    }

    public function delete() : bool
    {
        if (!parent::delete()) {
            throw new \DomainException('Brand removing error.');
        }

        return true;
    }

    public function behaviors() : array
    {
        return [
            [
                'class' => MetaBehavior::class,
                'json_attribute' => 'meta_json',
            ],
        ];
    }

    public function getSeoTitle() : string
    {
        return $this->meta->title ?: $this->name;
    }
}