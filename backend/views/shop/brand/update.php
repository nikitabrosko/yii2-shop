<?php

/** @var yii\web\View $this */
/** @var shop\entities\shop\Brand $brand */
/** @var shop\forms\manage\shop\BrandForm $model */

$this->title = 'Update Brand: ' . $brand->name;
$this->params['breadcrumbs'][] = ['label' => 'Brands', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $brand->name, 'url' => ['view', 'id' => $brand->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="brand-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
