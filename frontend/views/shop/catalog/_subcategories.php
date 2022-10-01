<?php

/* @var $category shop\entities\Shop\Category */

use yii\helpers\Html;
use yii\helpers\Url;
?>

<? if ($category->children): ?>
    <div class="list-group-item" style="padding: 15px; margin-bottom: 15px;">
        <? foreach ($category->children as $child): ?>
            <a href="<?= Html::encode(Url::to(['category', 'id' => $child->id])) ?>"><?= Html::encode($child->name) ?></a> &nbsp;
        <? endforeach; ?>
    </div>
<? endif; ?>