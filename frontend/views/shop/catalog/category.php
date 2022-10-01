<?php

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\DataProviderInterface */
/* @var $category shop\entities\shop\Category */

use yii\helpers\Html;

$this->title = $category->getSeoTitle();

$this->registerMetaTag(['name' => 'description', 'content' => $category->meta->description]);
$this->registerMetaTag(['name' => 'keywords', 'content' => $category->meta->keywords]);

$this->params['breadcrumbs'][] = ['label' => 'Catalog', 'url' => ['index']];
$this->params['breadcrumbs'][] = $category->name;
?>

<h1><?= Html::encode($category->getHeadingTile()) ?></h1>

<?= $this->render('_subcategories', [
    'category' => $category
])?>

<? if (trim($category->description)): ?>
    <div class="list-group-item" style="padding: 15px; margin-bottom: 15px; word-break: break-word;">
        <?= Yii::$app->formatter->asNtext($category->description) ?>
    </div>
<? endif; ?>

<?= $this->render('_list', [
    'dataProvider' => $dataProvider
])?>
