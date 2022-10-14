<?php

/* @var $this yii\web\View */
/* @var $model shop\forms\manage\shop\DiscountForm */

$this->title = 'Create Discount';
$this->params['breadcrumbs'][] = ['label' => 'Discounts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="method-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
