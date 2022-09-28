<?php

/** @var yii\web\View $this */
/** @var shop\entities\shop\Tag $tag */
/** @var shop\forms\manage\shop\TagForm $model */

$this->title = 'Update Tag: ' . $tag->name;
$this->params['breadcrumbs'][] = ['label' => 'Tags', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $tag->name, 'url' => ['view', 'id' => $tag->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tag-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
