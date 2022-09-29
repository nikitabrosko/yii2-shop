<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var shop\entities\shop\Characteristic $model */

$this->title = 'Create Characteristic';
$this->params['breadcrumbs'][] = ['label' => 'Characteristics', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="characteristic-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
