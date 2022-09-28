<?php

/** @var yii\web\View $this */
/** @var shop\forms\manage\shop\TagForm $model */

$this->title = 'Create Tag';
$this->params['breadcrumbs'][] = ['label' => 'Tags', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tag-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
