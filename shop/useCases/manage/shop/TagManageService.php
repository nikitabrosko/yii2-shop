<?php

namespace shop\useCases\manage\shop;

use shop\entities\shop\Tag;
use shop\forms\manage\shop\TagForm;
use yii\helpers\Inflector;

class TagManageService
{
    public function create(TagForm $form) : Tag
    {
        $tag = Tag::create(
            $form->name,
            $form->slug ?: Inflector::slug($form->name)
        );

        $tag->save();

        return $tag;
    }

    public function edit($id, TagForm $form)
    {
        $tag = Tag::findOne(['id' => $id]);

        $tag->edit(
            $form->name,
            $form->slug
        );

        $tag->save();
    }

    public function remove($id)
    {
        $tag = Tag::findOne(['id' => $id]);

        $tag->delete();
    }
}