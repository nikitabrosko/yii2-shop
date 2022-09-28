<?php

namespace backend\forms\shop;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use shop\entities\shop\Brand;

/**
 * BrandSearch represents the model behind the search form of `shop\entities\shop\Brand`.
 */
class BrandSearch extends Model
{
    public $id;
    public $name;
    public $slug;

    /**
     * {@inheritdoc}
     */
    public function rules() : array
    {
        return [
            [['id'], 'integer'],
            [['name', 'slug', 'meta_json'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Brand::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['name' => SORT_ASC],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'slug', $this->slug]);

        return $dataProvider;
    }
}
