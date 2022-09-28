<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var shop\entities\shop\Characteristic $model */

$this->title = 'Create Characteristic';
$this->params['breadcrumbs'][] = ['label' => 'Characteristics', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="characteristic-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
