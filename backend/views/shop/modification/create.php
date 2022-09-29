<?php

/** @var yii\web\View $this */
/** @var shop\entities\shop\product\Modification $product */
/* @var shop\forms\manage\shop\product\ModificationForm $model */

$this->title = 'Create Modification';
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['shop/product/index']];
$this->params['breadcrumbs'][] = ['label' => $product->name, 'url' => ['shop/product/view', 'id' => $product->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="modification-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
