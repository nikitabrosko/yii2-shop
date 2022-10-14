<?php

use kartik\widgets\DatePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model shop\forms\manage\shop\DiscountForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="method-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="box box-default">
        <div class="box-body">
            <?= $form->field($model, 'percent')->textInput() ?>
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'from_date', ['options' => ['type' => 'date']])
                ->widget(
                    DatePicker::class,[
                        'pluginOptions' => [
                            'format' => 'dd.mm.yyyy',
                            'todayHighlight' => true,
                        ],
                        'options' => [
                            'placeholder' => 'Enter from date',
                        ],
                        'pickerButton' => [
                            'style' => 'width: max-content',
                        ],
                        'removeButton' => [
                            'style' => 'width: max-content',
                        ]
                    ]) ?>
            <?= $form->field($model, 'to_date')
                ->widget(
                    DatePicker::class,[
                        'pluginOptions' => [
                            'format' => 'dd.mm.yyyy',
                            'todayHighlight' => true,
                        ],
                        'options' => [
                            'placeholder' => 'Enter to date',
                        ],
                        'pickerButton' => [
                            'style' => 'width: max-content',
                        ],
                        'removeButton' => [
                            'style' => 'width: max-content',
                        ]
                    ]) ?>
            <?= $form->field($model, 'sort')->textInput() ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
