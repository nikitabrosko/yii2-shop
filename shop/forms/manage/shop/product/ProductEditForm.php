<?php

namespace shop\forms\manage\shop\product;

use shop\entities\shop\Characteristic;
use shop\entities\shop\product\Product;
use shop\forms\CompositeForm;
use shop\forms\manage\MetaForm;

/**
 * @property MetaForm $meta
 * @property CategoriesForm $categories
 * @property TagsForm $tags
 * @property ValueForm[] $values
 */
class ProductEditForm extends CompositeForm
{
    public $brandId;
    public $code;
    public $name;

    private $_product;

    public function __construct(Product $product, $config = [])
    {
        $this->meta = new MetaForm($product->meta);
        $this->tags = new TagsForm($product);
        $this->values = array_map(function (Characteristic $characteristic) {
            return new ValueForm($characteristic, $this->_product->getValue($characteristic->id));
        }, Characteristic::find()->orderBy('sort')->all());
        $this->_product = $product;

        parent::__construct($config);
    }

    public function rules() : array
    {
        return [
            [['brandId', 'code', 'name'], 'required'],
            ['brandId', 'integer'],
            [['code', 'name'], 'string', 'max' => 255],
            ['code', 'unique', 'targetClass' => Product::class, 'filter' => $this->_product ? ['<>', 'id', $this->_product->id] : null]
        ];
    }

    protected function internalForms() : array
    {
        return ['meta', 'tags', 'values'];
    }
}