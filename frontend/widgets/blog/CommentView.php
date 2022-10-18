<?php

namespace frontend\widgets\blog;

use shop\entities\blog\post\Comment;

class CommentView
{
    public $comment;
    public $children;

    public function __construct(Comment $comment, array $children)
    {
        $this->comment = $comment;
        $this->children = $children;
    }
}