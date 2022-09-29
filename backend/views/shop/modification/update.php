<?php

/* @var yii\web\View $this */
/* @var shop\entities\shop\product\Product $product */
/* @var shop\entities\shop\product\Modification $modification */
/* @var shop\forms\manage\shop\product\ModificationForm $model */

$this->title = 'Update Modification: ' . $modification->name;
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['shop/product/index']];
$this->params['breadcrumbs'][] = ['label' => $product->name, 'url' => ['shop/product/view', 'id' => $product->id]];
$this->params['breadcrumbs'][] = $modification->name;
?>
<div class="modification-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
