<?php

namespace shop\useCases\manage\shop;

use shop\entities\shop\DeliveryMethod;
use shop\exceptions\NotFoundException;
use shop\forms\manage\shop\DeliveryMethodForm;

class DeliveryMethodManageService
{
    public function create(DeliveryMethodForm $form): DeliveryMethod
    {
        $method = DeliveryMethod::create(
            $form->name,
            $form->cost,
            $form->minWeight,
            $form->maxWeight,
            $form->sort
        );

        $method->save();

        return $method;
    }

    public function edit($id, DeliveryMethodForm $form)
    {
        $method = $this->getDeliveryMethod($id);

        $method->edit(
            $form->name,
            $form->cost,
            $form->minWeight,
            $form->maxWeight,
            $form->sort
        );

        $method->save();
    }

    public function remove($id)
    {
        $method = $this->getDeliveryMethod($id);

        $method->delete();
    }

    private function getDeliveryMethod($id) : DeliveryMethod
    {
        if (!$tag = DeliveryMethod::findOne(['id' => $id])) {
            throw new NotFoundException('Tag not found.');
        }

        return $tag;
    }
}