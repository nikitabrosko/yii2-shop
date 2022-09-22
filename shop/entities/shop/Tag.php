<?php

namespace shop\entities\shop;

use yii\db\ActiveRecord;

/**
 * @property integer id
 * @property string $name
 * @property string $slug
 */
class Tag extends ActiveRecord
{
    public static function create($name, $slug) : self
    {
        $tag = new static();
        $tag->name = $name;
        $tag->slug = $slug;

        return $tag;
    }

    public function edit(string $name = null, string $slug = null)
    {
        if ($name) {
            $this->name = $name;
        }

        if ($slug) {
            $this->slug = $slug;
        }
    }

    public function save($runValidation = true, $attributeNames = null) : bool
    {
        if (!parent::save($runValidation, $attributeNames)) {
            throw new \DomainException('Tag saving error.');
        }

        return true;
    }

    public function delete() : int
    {
        if (!$id = parent::delete()) {
            throw new \DomainException('Tag removing error.');
        }

        return $id;
    }

    public static function tableName() : string
    {
        return '{{%shop_tags}}';
    }
}