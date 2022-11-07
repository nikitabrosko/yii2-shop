<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;

?>
<?php $this->beginContent('@frontend/views/layouts/main.php') ?>

<div class="row">
    <div id="content" class="col-sm-9">
        <?= $content ?>
    </div>
    <aside id="column-right" class="col-sm-3 hidden-xs">
        <div class="list-group">
            <? if(\Yii::$app->user->isGuest): ?>
                <a href="<?= Html::encode(Url::to(['/auth/auth/login'])) ?>" class="list-group-item">Login</a>
                <a href="<?= Html::encode(Url::to(['/auth/signup/signup'])) ?>" class="list-group-item">Signup</a>
                <a href="<?= Html::encode(Url::to(['/auth/reset/request'])) ?>" class="list-group-item">Forgotten Password</a>
            <? else: ?>
                <a href="<?= Html::encode(Url::to(['/cabinet/default/cabinet'])) ?>" class="list-group-item">My Account</a>
                <a href="<?= Html::encode(Url::to(['/cabinet/wishlist/wishlist'])) ?>" class="list-group-item">Wish List</a>
                <a href="<?= Html::encode(Url::to(['/cabinet/order/orders'])) ?>" class="list-group-item">Orders History</a>
            <? endif; ?>
        </div>
    </aside>
</div>

<?php $this->endContent() ?>
