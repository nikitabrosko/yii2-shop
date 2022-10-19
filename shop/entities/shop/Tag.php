<?php

namespace shop\entities\shop;

use shop\exceptions\DeleteErrorException;
use shop\exceptions\SavingErrorException;
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

    public function edit(string $name, string $slug)
    {
        $this->name = $name;
        $this->slug = $slug;
    }

    public static function tableName() : string
    {
        return '{{%shop_tags}}';
    }

    public function save($runValidation = true, $attributeNames = null) : bool
    {
        if (!parent::save($runValidation, $attributeNames)) {
            throw new SavingErrorException('Tag saving error.');
        }

        return true;
    }

    public function delete() : bool
    {
        if (!parent::delete()) {
            throw new DeleteErrorException('Tag deleting error.');
        }

        return true;
    }
}