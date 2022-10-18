<?php

/* @var $this yii\web\View */
/* @var $model shop\entities\blog\post\Post */

use yii\helpers\Html;
use yii\helpers\Url;

$url = Url::to(['post', 'id' =>$model->id]);
?>

<div class="card mb-3">
    <?php if ($model->photo): ?>
        <div class="card-img-top">
            <a href="<?= Html::encode($url) ?>">
                <img src="<?= Html::encode($model->getThumbFileUrl('photo', 'blog_list')) ?>" alt="" class="img-responsive" />
            </a>
        </div>
    <?php endif; ?>
    <div class="card-header h2">
        <a href="<?= Html::encode($url) ?>"><?= Html::encode($model->title) ?></a>
    </div>
    <p class="card-body"><?= Yii::$app->formatter->asNtext($model->description) ?></p>
</div>


