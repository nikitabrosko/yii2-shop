<?php

namespace shop\useCases\manage\blog;

use shop\entities\blog\post\Post;
use shop\exceptions\NotFoundException;
use shop\forms\manage\blog\post\CommentEditForm;

class CommentManageService
{
    public function edit($postId, $id, CommentEditForm $form): void
    {
        $post = $this->getPostById($postId);

        $post->editComment($id, $form->parentId, $form->text);

        $post->save();
    }

    public function activate($postId, $id): void
    {
        $post = $this->getPostById($postId);

        $post->activateComment($id);

        $post->save();
    }

    public function remove($postId, $id): void
    {
        $post = $this->getPostById($postId);

        $post->removeComment($id);

        $post->save();
    }

    private function getPostById($id) : Post
    {
        if (!$post = Post::findOne($id)) {
            throw new NotFoundException('Post not found.');
        }

        return $post;
    }
}