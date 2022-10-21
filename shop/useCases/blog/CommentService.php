<?php

namespace shop\useCases\blog;

use shop\entities\blog\post\Comment;
use shop\entities\blog\post\Post;
use shop\entities\user\User;
use shop\exceptions\NotFoundException;
use shop\forms\blog\CommentForm;

class CommentService
{
    public function create($postId, $userId, CommentForm $form) : Comment
    {
        if (!$post = Post::findOne($postId)) {
            throw new NotFoundException('Post not found.');
        }

        if (!$user = User::findOne($userId)) {
            throw new NotFoundException('User not found.');
        }

        $comment = $post->addComment($user->id, $form->parentId, $form->text);

        $post->save();

        return $comment;
    }
}