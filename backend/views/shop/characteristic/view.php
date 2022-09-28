<?php

use shop\helpers\CharacteristicHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var shop\entities\shop\Characteristic $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Characteristics', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="characteristic-view">
    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="box">
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    'name',
                    [
                        'attribute' => 'type',
                        'value' => CharacteristicHelper::typeName($model->type),
                    ],
                    'sort',
                    'required:boolean',
                    'default',
                    [
                        'attribute' => 'variants',
                        'value' => implode(PHP_EOL, $model->variants),
                        'format' => 'ntext',
                    ],
                ],
            ]) ?>
        </div>
    </div>
</div>
