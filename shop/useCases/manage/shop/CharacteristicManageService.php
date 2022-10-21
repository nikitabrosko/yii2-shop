<?php

namespace shop\useCases\manage\shop;

use shop\entities\shop\Characteristic;
use shop\forms\manage\shop\CharacteristicForm;

class CharacteristicManageService
{
    public function create(CharacteristicForm $form) : Characteristic
    {
        $characteristic = Characteristic::create(
            $form->name,
            $form->type,
            $form->required,
            $form->default,
            $form->variants,
            $form->sort
        );

        $characteristic->save();

        return $characteristic;
    }

    public function edit($id, CharacteristicForm $form)
    {
        $characteristic = Characteristic::findOne(['id' => $id]);

        $characteristic->edit(
            $form->name,
            $form->type,
            $form->required,
            $form->default,
            $form->variants,
            $form->sort
        );

        $characteristic->save();
    }

    public function remove($id)
    {
        $category = Characteristic::findOne(['id' => $id]);

        $category->delete();
    }
}