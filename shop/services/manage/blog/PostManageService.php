<?php

namespace shop\services\manage\blog;

use shop\entities\blog\Category;
use shop\entities\Meta;
use shop\entities\Blog\Post\Post;
use shop\entities\Blog\Tag;
use shop\exceptions\NotFoundException;
use shop\forms\manage\Blog\Post\PostForm;
use shop\services\manage\TransactionManager;

class PostManageService
{
    private $transaction;

    public function __construct(TransactionManager $transaction)
    {
        $this->transaction = $transaction;
    }

    public function create(PostForm $form): Post
    {
        $category = $this->getCategoryById($form->categoryId);

        $post = Post::create(
            $category->id,
            $form->title,
            $form->description,
            $form->content,
            new Meta(
                $form->meta->title,
                $form->meta->description,
                $form->meta->keywords
            )
        );

        if ($form->photo) {
            $post->setPhoto($form->photo);
        }

        foreach ($form->tags->existing as $tagId) {
            $tag = $this->getTagById($tagId);
            $post->assignTag($tag->id);
        }

        $this->transaction->wrap(function () use ($post, $form) {
            foreach ($form->tags->newNames as $tagName) {
                if (!$tag = $this->getTagByName($tagName)) {
                    $tag = Tag::create($tagName, $tagName);

                    $tag->save();
                }

                $post->assignTag($tag->id);
            }

            $post->save();
        });

        return $post;
    }

    public function edit($id, PostForm $form): void
    {
        $post = $this->getPostById($id);
        $category = $this->getCategoryById($form->categoryId);

        $post->edit(
            $category->id,
            $form->title,
            $form->description,
            $form->content,
            new Meta(
                $form->meta->title,
                $form->meta->description,
                $form->meta->keywords
            )
        );

        if ($form->photo) {
            $post->setPhoto($form->photo);
        }

        $this->transaction->wrap(function () use ($post, $form) {

            $post->revokeTags();
            $post->save();

            foreach ($form->tags->existing as $tagId) {
                $tag = $this->getTagById($tagId);
                $post->assignTag($tag->id);
            }
            foreach ($form->tags->newNames as $tagName) {
                if (!$tag = $this->getTagByName($tagName)) {
                    $tag = Tag::create($tagName, $tagName);

                    $tag->save();
                }

                $post->assignTag($tag->id);
            }

            $post->save();
        });
    }

    public function activate($id): void
    {
        $post = $this->getPostById($id);
        $post->activate();

        $post->save();
    }

    public function draft($id): void
    {
        $post = $this->getPostById($id);
        $post->draft();

        $post->save();
    }

    public function remove($id): void
    {
        $post = $this->getPostById($id);

        $post->delete();
    }

    private function getPostById($id) : Post
    {
        if (!$post = Post::findOne($id)) {
            throw new NotFoundException('Post not found.');
        }

        return $post;
    }

    private function getCategoryById($id) : Category
    {
        if (!$category = Category::findOne($id)) {
            throw new NotFoundException('Category not found.');
        }

        return $category;
    }

    private function getTagById($id) : Tag
    {
        if (!$tag = Tag::findOne($id)) {
            throw new NotFoundException('Tag not found.');
        }

        return $tag;
    }

    private function getTagByName($name) : Tag
    {
        if (!$tag = Tag::findOne(['name' => $name])) {
            throw new NotFoundException('Tag not found.');
        }

        return $tag;
    }
}