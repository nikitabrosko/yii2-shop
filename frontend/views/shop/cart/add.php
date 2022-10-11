<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model shop\forms\shop\AddToCartForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Add';
$this->params['breadcrumbs'][] = ['label' => 'Catalog', 'url' => ['/shop/catalog/catalog']];
$this->params['breadcrumbs'][] = ['label' => 'Cart', 'url' => ['cart']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin() ?>

            <?php if ($modifications = $model->modificationsList()): ?>
                <div class="mb-3">
                    <?= $form->field($model, 'modification')->dropDownList($modifications,
                        ['prompt' => 'Select modification', 'class' => 'form-select']) ?>
                </div>
            <?php endif; ?>

            <div class="mb-3">
                <?= $form->field($model, 'quantity')->textInput() ?>
            </div>

            <div class="mb-3">
                <?= Html::submitButton('Add to Cart', ['class' => 'btn btn-primary btn-lg btn-block']) ?>
            </div>

            <? ActiveForm::end() ?>
        </div>
    </div>
</div>
