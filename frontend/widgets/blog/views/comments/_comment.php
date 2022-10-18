<?php

/* @var $item frontend\widgets\blog\CommentView */
?>

<div class="comment-item" data-id="<?= $item->comment->id ?>">
    <div class="card mb-3">
        <div class="card-body">
            <p class="comment-item">
                <?php if ($item->comment->isActive()): ?>
                    User <?= Yii::$app->formatter->asNtext($item->comment->getUsername()) ?> write:
                <?php endif; ?>
            </p>
            <p class="comment-content">
                <?php if ($item->comment->isActive()): ?>
                    <?= Yii::$app->formatter->asNtext($item->comment->text) ?>
                <?php else: ?>
                    <i>Comment is deleted.</i>
                <?php endif; ?>
            </p>
            <div>
                <div class="pull-left">
                    <?= Yii::$app->formatter->asDatetime($item->comment->created_at) ?>
                </div>
                <div class="pull-right">
                    <span class="comment-reply">Reply</span>
                </div>
            </div>
        </div>
    </div>
    <div class="margin">
        <div class="reply-block"></div>
        <div class="comments">
            <?php foreach ($item->children as $children): ?>
                <?= $this->render('_comment', ['item' => $children]) ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>