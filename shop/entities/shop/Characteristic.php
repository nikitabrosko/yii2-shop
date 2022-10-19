<?php

namespace shop\entities\shop;

use shop\entities\behaviors\JsonArrayBehavior;
use shop\exceptions\DeleteErrorException;
use shop\exceptions\SavingErrorException;
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

    public function edit(string $name, string $type, string $required,
                         string $default, array $variants, int $sort)
    {
        $this->name = $name;
        $this->type = $type;
        $this->required = $required;
        $this->default = $default;
        $this->variants = $variants;
        $this->sort = $sort;
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
            throw new SavingErrorException('Characteristic saving error.');
        }

        return true;
    }

    public function delete() : bool
    {
        if (!parent::delete()) {
            throw new DeleteErrorException('Characteristic deleting error.');
        }

        return true;
    }
}