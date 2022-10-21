<?php

namespace shop\useCases\manage\shop;

use shop\entities\Meta;
use shop\entities\shop\Brand;
use shop\entities\shop\product\Product;
use shop\exceptions\NotFoundException;
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
                $form->meta->title ?? null,
                $form->meta->description ?? null,
                $form->meta->keywords ?? null
            )
        );

        $brand->save();

        return $brand;
    }

    public function edit($id, BrandForm $form)
    {
        $brand = $this->getBrand($id);

        $brand->edit(
            $form->name,
            $form->slug,
            new Meta(
                $form->meta->title ?? null,
                $form->meta->description ?? null,
                $form->meta->keywords ?? null
            )
        );

        $brand->save();
    }

    public function remove($id)
    {
        $brand = $this->getBrand($id);

        if (Product::find()->andWhere(['brand_id' => $id])->exists()) {
            throw new \DomainException('Unable to delete brand with products.');
        }

        $brand->delete();
    }

    private function getBrand($id) : Brand
    {
        if (!$brand = Brand::findOne(['id' => $id])) {
            throw new NotFoundException('Brand not found.');
        }

        return $brand;
    }
}