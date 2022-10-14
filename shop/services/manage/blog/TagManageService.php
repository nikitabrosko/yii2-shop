<?php

namespace shop\services\manage\blog;

use shop\entities\blog\Tag;
use shop\forms\manage\blog\TagForm;

class TagManageService
{
    public function create(TagForm $form): Tag
    {
        $tag = Tag::create(
            $form->name,
            $form->slug
        );

        $tag->save();

        return $tag;
    }

    public function edit($id, TagForm $form): void
    {
        $tag = $this->getTag($id);

        $tag->edit(
            $form->name,
            $form->slug
        );

        $tag->save();
    }

    public function remove($id): void
    {
        $tag = $this->getTag($id);

        $tag->delete();
    }

    private function getTag($id) : Tag
    {
        return Tag::findOne($id);
    }
}