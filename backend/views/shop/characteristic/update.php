<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var shop\entities\shop\Characteristic $characteristic  */
/** @var shop\forms\manage\Shop\CharacteristicForm $model  */

$this->title = 'Update Characteristic: ' . $characteristic->name;
$this->params['breadcrumbs'][] = ['label' => 'Characteristics', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $characteristic->name, 'url' => ['view', 'id' => $characteristic->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="characteristic-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
