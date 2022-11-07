<?php

namespace shop\useCases\cabinet;

use shop\entities\user\User;
use shop\exceptions\NotFoundException;
use shop\forms\user\ProfileEditForm;

class ProfileService
{
    public function edit($id, ProfileEditForm $form): void
    {
        $user = $this->getUser($id);

        $user->editProfile($form->email, $form->phone);

        $user->save();
    }

    private function getUser($id) : User
    {
        if (!$user = User::findOne(['id' => $id])) {
            throw new NotFoundException('User not found.');
        }

        return $user;
    }
}