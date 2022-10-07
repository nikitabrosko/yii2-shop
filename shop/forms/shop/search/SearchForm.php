<?php

namespace shop\forms\shop\search;

use shop\entities\shop\Brand;
use shop\entities\shop\Category;
use shop\entities\shop\Characteristic;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * @property ValueForm[] $values
 */
class SearchForm extends Model
{
    public $text;
    public $category;
    public $brand;
    public $values;

    public function __construct(array $config = [])
    {
        $this->values = array_map(function (Characteristic $characteristic) {
            return new ValueForm($characteristic);
        }, Characteristic::find()->orderBy('sort')->all());

        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            ['text', 'string'],
            [['category', 'brand'], 'integer'],
        ];
    }

    public function categoriesList(): array
    {
        return ArrayHelper::map(Category::find()->andWhere(['>', 'depth', 0])->orderBy('lft')->asArray()->all(), 'id', function (array $category) {
            return ($category['depth'] > 1 ? str_repeat('-- ', $category['depth'] - 1) . ' ' : '') . $category['name'];
        });
    }

    public function brandsList(): array
    {
        return ArrayHelper::map(Brand::find()->orderBy('name')->asArray()->all(), 'id', 'name');
    }

    public function formName(): string
    {
        return '';
    }

    protected function internalForms(): array
    {
        return ['values'];
    }
}