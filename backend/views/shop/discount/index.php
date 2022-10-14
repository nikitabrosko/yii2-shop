<?php

use kartik\widgets\DatePicker;
use shop\entities\shop\Discount;
use shop\helpers\DiscountHelper;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\shop\DiscountSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Discounts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <p>
        <?= Html::a('Create Discount', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="box">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    'id',
                    'percent',
                    [
                        'attribute' => 'name',
                        'value' => function (Discount $model) {
                            return Html::a(Html::encode($model->name), ['view', 'id' => $model->id]);
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'from_date',
                        'filter' => DatePicker::widget([
                            'model' => $searchModel,
                            'attribute' => 'from_date_from',
                            'attribute2' => 'from_date_to',
                            'type' => DatePicker::TYPE_RANGE,
                            'separator' => '-',
                            'pluginOptions' => [
                                'todayHighlight' => true,
                                'autoclose' => true,
                                'format' => 'dd.mm.yyyy',
                            ],
                        ]),
                        'format' => 'date',
                    ],
                    [
                        'attribute' => 'to_date',
                        'filter' => DatePicker::widget([
                            'model' => $searchModel,
                            'attribute' => 'to_date_from',
                            'attribute2' => 'to_date_to',
                            'type' => DatePicker::TYPE_RANGE,
                            'separator' => '-',
                            'pluginOptions' => [
                                'todayHighlight' => true,
                                'autoclose' => true,
                                'format' => 'dd.mm.yyyy',
                            ],
                        ]),
                        'format' => 'date',
                    ],
                    [
                        'attribute' => 'active',
                        'filter' => DiscountHelper::statusList(),
                        'value' => function (Discount $model) {
                            return DiscountHelper::statusLabel($model->active);
                        },
                        'format' => 'raw',
                    ],
                    ['class' => ActionColumn::class],
                ],
            ]); ?>
        </div>
    </div>
</div>
