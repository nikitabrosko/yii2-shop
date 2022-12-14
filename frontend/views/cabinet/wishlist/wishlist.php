<?php

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\DataProviderInterface */

use shop\entities\Shop\Product\Product;
use shop\helpers\PriceHelper;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'Wish List';
$this->params['breadcrumbs'][] = ['label' => 'Cabinet', 'url' => ['cabinet/default/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cabinet-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'value' => function (Product $model) {
                    return $model->mainPhoto ? Html::img($model->mainPhoto->getThumbFileUrl('file', 'wishlist')) : null;
                },
                'format' => 'raw',
                'contentOptions' => ['style' => 'width: 100px'],
            ],
            [
                'attribute' => 'name',
                'value' => function (Product $model) {
                    return Html::a(Html::encode($model->name), ['/shop/catalog/product', 'id' => $model->id]);
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'price_new',
                'value' => function (Product $model) {
                    return PriceHelper::format($model->price_new);
                },
            ],
            [
                'attribute' => 'price_old',
                'value' => function (Product $model) {
                    return PriceHelper::format($model->price_old);
                },
            ],
            [
                'class' => ActionColumn::class,
                'template' => '{delete}',
            ],
        ],
    ]); ?>

</div>
