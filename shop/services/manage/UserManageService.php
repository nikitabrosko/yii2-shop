<?php

namespace shop\services\manage;

use shop\entities\user\User;
use shop\exceptions\NotFoundException;
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
            $form->email
        );

        if (!$user->save()) {
            throw new \DomainException('Save error.');
        }
    }

    public function activate($id): void
    {
        $user = $this->getUserById($id);
        $user->activate();

        $user->save();
    }

    public function disable($id): void
    {
        $user = $this->getUserById($id);
        $user->disable();

        $user->save();
    }

    private function getUserById($id) : User
    {
        if (!$post = User::findOne($id)) {
            throw new NotFoundException('User not found.');
        }

        return $post;
    }
}