<?php

use shop\helpers\DiscountHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $discount shop\entities\shop\Discount */

$this->title = $discount->name;
$this->params['breadcrumbs'][] = ['label' => 'Discounts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <p>
        <?php if ($discount->active): ?>
            <?= Html::a('Draft', ['draft', 'id' => $discount->id], ['class' => 'btn btn-primary', 'data-method' => 'post']) ?>
        <?php else: ?>
            <?= Html::a('Activate', ['activate', 'id' => $discount->id], ['class' => 'btn btn-success', 'data-method' => 'post']) ?>
        <?php endif; ?>

        <?= Html::a('Update', ['update', 'id' => $discount->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $discount->id], [
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
                'model' => $discount,
                'attributes' => [
                    'id',
                    [
                        'attribute' => 'active',
                        'value' => DiscountHelper::statusLabel($discount->active),
                        'format' => 'raw',
                    ],
                    'percent',
                    'name',
                    [
                        'attribute' => 'from_date',
                        'value' => $discount->from_date,
                        'format' => 'date',
                    ],
                    [
                        'attribute' => 'to_date',
                        'value' => $discount->to_date,
                        'format' => 'date',
                    ],
                ],
            ]) ?>
        </div>
    </div>
</div>
