<?php

namespace backend\forms\shop;

use shop\entities\shop\Discount;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class DiscountSearch extends Model
{
    public $id;
    public $name;
    public $percent;
    public $from_date_from;
    public $from_date_to;
    public $to_date_from;
    public $to_date_to;
    public $active;

    public function rules()
    {
        return [
            ['name', 'string', 'max' => 255],
            [['id', /*'active',*/ 'percent'], 'integer'],
            [['from_date_from', 'from_date_to', 'to_date_from', 'to_date_to'], 'date', 'format' => 'php:d.m.Y'],
        ];
    }

    public function search($params)
    {
        $query = Discount::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'name' => $this->name,
            'percent' => $this->percent,
            'active' => $this->active,
        ]);

        $query
            ->andFilterWhere(['>=', 'from_date', $this->from_date_from ? strtotime($this->from_date_from . ' 00:00:00') : null])
            ->andFilterWhere(['<=', 'from_date', $this->from_date_to ? strtotime($this->from_date_to . ' 23:59:59') : null])
            ->andFilterWhere(['>=', 'to_date', $this->to_date_from ? strtotime($this->to_date_from . ' 00:00:00') : null])
            ->andFilterWhere(['<=', 'to_date', $this->to_date_to ? strtotime($this->to_date_to . ' 23:59:59') : null]);

        return $dataProvider;
    }
}