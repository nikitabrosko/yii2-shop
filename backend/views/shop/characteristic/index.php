<?php

use shop\entities\shop\Characteristic;
use shop\helpers\CharacteristicHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\forms\shop\CharacteristicSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Characteristics';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="characteristic-index">
    <p>
        <?= Html::a('Create Characteristic', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="box">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    [
                        'attribute' => 'name',
                        'value' => function (Characteristic $model) {
                            return Html::a(Html::encode($model->name), ['view', 'id' => $model->id]);
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'type',
                        'filter' => $searchModel->typesList(),
                        'value' => function (Characteristic $model) {
                            return CharacteristicHelper::typeName($model->type);
                        },
                    ],
                    [
                        'attribute' => 'required',
                        'filter' => $searchModel->requiredList(),
                        'format' => 'boolean',
                    ],
                    ['class' => ActionColumn::class],
                ],
            ]); ?>
        </div>
    </div>
</div>
