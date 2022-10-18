<?php

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\DataProviderInterface */

use yii\widgets\ListView;

?>

<?= ListView::widget([
    'dataProvider' => $dataProvider,
    'itemView' => '_post',
]) ?>
