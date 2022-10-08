<?php

/* @var $category shop\entities\Shop\Category */

use yii\helpers\Html;
use yii\helpers\Url;
?>

<? if ($category->children): ?>
    <div class="card mb-3">
        <div class="card-body">
            <? foreach ($category->children as $child): ?>
                <a href="<?= Html::encode(Url::to(['category', 'id' => $child->id])) ?>"><?= Html::encode($child->name) ?></a> &nbsp;
            <? endforeach; ?>
        </div>
    </div>
<? endif; ?>