<?php

namespace shop\services\manage\shop;

use shop\entities\shop\Discount;
use shop\forms\manage\shop\DiscountForm;
use shop\helpers\DateConverterHelper;

class DiscountManageService
{
    public function create(DiscountForm $form) : Discount
    {
        $discount = Discount::create(
            $form->percent,
            $form->name,
            DateConverterHelper::convertDateToTimestamp($form->from_date),
            DateConverterHelper::convertDateToTimestamp($form->to_date),
            $form->sort
        );

        $discount->save();

        return $discount;
    }

    public function edit($id, DiscountForm $form)
    {
        $discount = $this->getDiscount($id);

        $discount->edit(
            $form->percent,
            $form->name,
            DateConverterHelper::convertDateToTimestamp($form->from_date),
            DateConverterHelper::convertDateToTimestamp($form->to_date),
            $form->sort
        );

        $discount->save();
    }

    public function remove($id)
    {
        $this->getDiscount($id)->delete();
    }

    public function activate($id)
    {
        $discount = $this->getDiscount($id);
        $discount->activate();

        $discount->save();
    }

    public function draft($id)
    {
        $discount = $this->getDiscount($id);
        $discount->draft();

        $discount->save();
    }

    private function getDiscount($id) : Discount
    {
        return Discount::findOne(['id' => $id]);
    }
}