<?php

namespace shop\services\manage;

use shop\entities\user\User;
use shop\forms\manage\user\UserCreateForm;
use shop\forms\manage\user\UserEditForm;

class UserManageService
{
    public function create(UserCreateForm $form) : User
    {
        $user = User::create(
            $form->username,
            $form->email,
            $form->password
        );

        if (!$user->save()) {
            throw new \DomainException('Save error.');
        }

        return $user;
    }

    public function edit($id, UserEditForm $form)
    {
        $user = User::findOne([
            'id' => $id,
        ]);

        $user->edit(
            $form->username,
            $form->email,
        );

        if (!$user->save()) {
            throw new \DomainException('Save error.');
        }
    }
}