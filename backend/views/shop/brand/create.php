<?php

/** @var yii\web\View $this */
/** @var shop\forms\manage\shop\BrandForm $model */

$this->title = 'Create Brand';
$this->params['breadcrumbs'][] = ['label' => 'Brands', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="brand-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
