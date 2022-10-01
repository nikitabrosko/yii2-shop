<?php

namespace shop\entities\shop;

use paulzi\nestedsets\NestedSetsBehavior;
use shop\entities\behaviors\MetaBehavior;
use shop\entities\Meta;
use shop\entities\shop\queries\CategoryQuery;
use yii\db\ActiveRecord;

/**
 * @property integer $id
 * @property string $name
 * @property string $slug
 * @property string $title
 * @property string $description
 * @property integer $lft
 * @property integer $rgt
 * @property integer $depth
 * @property Meta $meta
 *
 * @property Category $parent
 * @property Category[] $parents
 * @property Category[] $children
 * @property Category $prev
 * @property Category $next
 * @mixin NestedSetsBehavior
 */
class Category extends ActiveRecord
{
    public $meta;

    public static function create(string $name, string $slug, string $title,
                                  string $description, Meta $meta) : self
    {
        $category = new static();
        $category->name = $name;
        $category->slug = $slug;
        $category->title = $title;
        $category->description = $description;
        $category->meta = $meta;

        return $category;
    }

    public function edit(string $name = null, string $slug = null, string $title = null,
                         string $description = null, Meta $meta = null)
    {
        if ($name) $this->name = $name;
        if ($slug) $this->slug = $slug;
        if ($title) $this->title = $title;
        if ($description) $this->description = $description;
        if ($meta) $this->meta = $meta;
    }

    public static function tableName() : string
    {
        return '{{%shop_categories}}';
    }

    public function save($runValidation = true, $attributeNames = null) : bool
    {
        if (!parent::save($runValidation, $attributeNames)) {
            throw new \DomainException('Category saving error.');
        }

        return true;
    }

    public function delete() : bool
    {
        if (!parent::delete()) {
            throw new \DomainException('Category removing error.');
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
            NestedSetsBehavior::class,
        ];
    }

    public function transactions() : array
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find() : CategoryQuery
    {
        return new CategoryQuery(static::class);
    }

    public function getSeoTitle() : string
    {
        return $this->meta->title ?: ($this->title ?: $this->name);
    }

    public function getHeadingTile() : string
    {
        return $this->title ?: $this->name;
    }
}