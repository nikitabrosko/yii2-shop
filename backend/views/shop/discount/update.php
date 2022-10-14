<?php

/* @var $this yii\web\View */
/* @var $discount shop\entities\shop\Discount */
/* @var $model shop\forms\manage\shop\DiscountForm */

$this->title = 'Update Discount: ' . $discount->name;
$this->params['breadcrumbs'][] = ['label' => 'Discounts', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $discount->name, 'url' => ['view', 'id' => $discount->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="method-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
