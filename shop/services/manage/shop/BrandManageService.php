<?php

namespace shop\services\manage\shop;

use shop\entities\Meta;
use shop\entities\shop\Brand;
use shop\forms\manage\shop\BrandForm;
use yii\helpers\Inflector;

class BrandManageService
{
    public function create(BrandForm $form) : Brand
    {
        $brand = Brand::create(
            $form->name,
            $form->slug ?: Inflector::slug($form->name),
            new Meta(
                $form->meta->title,
                $form->meta->description,
                $form->meta->keywords
            )
        );

        $brand->save();

        return $brand;
    }

    public function edit($id, BrandForm $form)
    {
        $brand = Brand::findOne(['id' => $id]);

        $brand->edit(
            $form->name,
            $form->slug,
            new Meta(
                $form->meta->title,
                $form->meta->description,
                $form->meta->keywords
            )
        );

        $brand->save();
    }

    public function remove($id)
    {
        $brand = Brand::findOne(['id' => $id]);

        $brand->delete();
    }
}