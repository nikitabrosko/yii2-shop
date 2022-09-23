<?php

namespace shop\entities\shop;

use shop\entities\behaviors\JsonArrayBehavior;
use yii\db\ActiveRecord;

/**
 * @property integer $id
 * @property string $name
 * @property string $type
 * @property string $required
 * @property string $default
 * @property array $variants
 * @property integer $sort
 */
class Characteristic extends ActiveRecord
{
    const TYPE_STRING = 'string';
    const TYPE_INTEGER = 'integer';
    const TYPE_FLOAT = 'float';

    public $variants;

    public static function create(string $name, string $type, string $required,
                                  string $default, array $variants, int $sort) : self
    {
        $characteristic = new static();
        $characteristic->name = $name;
        $characteristic->type = $type;
        $characteristic->required = $required;
        $characteristic->default = $default;
        $characteristic->variants = $variants;
        $characteristic->sort = $sort;

        return $characteristic;
    }

    public function edit(string $name = null, string $type = null, string $required = null,
                         string $default = null, array $variants = null, int $sort = null)
    {
        if ($name) $this->name = $name;
        if ($type) $this->type = $type;
        if ($required) $this->required = $required;
        if ($default) $this->default = $default;
        if ($variants) $this->variants = $variants;
        if ($sort) $this->sort = $sort;
    }

    public function isString(): bool
    {
        return $this->type === self::TYPE_STRING;
    }

    public function isInteger(): bool
    {
        return $this->type === self::TYPE_INTEGER;
    }

    public function isFloat(): bool
    {
        return $this->type === self::TYPE_FLOAT;
    }

    public function isSelect() : bool
    {
        return count($this->variants) > 0;
    }

    public static function tableName() : string
    {
        return '{{%shop_characteristics}}';
    }

    public function behaviors() : array
    {
        return [
            [
                'class' => JsonArrayBehavior::class,
                'attribute' => 'variants',
                'json_attribute' => 'variants_json',
            ],
        ];
    }

    public function save($runValidation = true, $attributeNames = null) : bool
    {
        if (!parent::save($runValidation, $attributeNames)) {
            throw new \DomainException('Characteristic saving error.');
        }

        return true;
    }

    public function delete() : bool
    {
        if (!parent::delete()) {
            throw new \DomainException('Characteristic removing error.');
        }

        return true;
    }
}