<?php

namespace shop\useCases\manage;

use shop\entities\Meta;
use shop\entities\Page;
use shop\exceptions\NotFoundException;
use shop\forms\manage\PageForm;

class PageManageService
{
    public function create(PageForm $form): Page
    {
        $parent = $this->getPageById($form->parentId);

        $page = Page::create(
            $form->title,
            $form->slug,
            $form->content,
            new Meta(
                $form->meta->title,
                $form->meta->description,
                $form->meta->keywords
            )
        );

        $page->appendTo($parent);

        $page->save();

        return $page;
    }

    public function edit($id, PageForm $form): void
    {
        $page = $this->getPageById($id);

        $this->assertIsNotRoot($page);

        $page->edit(
            $form->title,
            $form->slug,
            $form->content,
            new Meta(
                $form->meta->title,
                $form->meta->description,
                $form->meta->keywords
            )
        );

        if ($form->parentId !== $page->parent->id) {
            $parent = $this->getPageById($form->parentId);

            $page->appendTo($parent);
        }

        $page->save();
    }

    public function moveUp($id): void
    {
        $page = $this->getPageById($id);

        $this->assertIsNotRoot($page);

        if ($prev = $page->prev) {
            $page->insertBefore($prev);
        }

        $page->save();
    }

    public function moveDown($id): void
    {
        $page = $this->getPageById($id);

        $this->assertIsNotRoot($page);

        if ($next = $page->next) {
            $page->insertAfter($next);
        }

        $page->save();
    }

    public function remove($id): void
    {
        $page = $this->getPageById($id);

        $this->assertIsNotRoot($page);

        $page->delete();
    }

    private function assertIsNotRoot(Page $page): void
    {
        if ($page->isRoot()) {
            throw new \DomainException('Unable to manage the root page.');
        }
    }

    private function getPageById($id) : Page
    {
        if (!$post = Page::findOne($id)) {
            throw new NotFoundException('Page not found.');
        }

        return $post;
    }
}