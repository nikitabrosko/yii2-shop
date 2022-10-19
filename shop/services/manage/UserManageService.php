<?php

namespace shop\services\manage;

use shop\entities\user\User;
use shop\exceptions\DeleteErrorException;
use shop\exceptions\NotFoundException;
use shop\exceptions\SavingErrorException;
use shop\forms\manage\user\UserCreateForm;
use shop\forms\manage\user\UserEditForm;
use shop\services\RoleManager;
use shop\services\TransactionManager;
use yii\mail\MailerInterface;

class UserManageService
{
    private $roleManager;
    private $transactionManager;

    public function __construct(
        RoleManager $roleManager,
        TransactionManager $transactionManager)
    {
        $this->roleManager = $roleManager;
        $this->transactionManager = $transactionManager;
    }

    public function create(UserCreateForm $form) : User
    {
        $user = User::create(
            $form->username,
            $form->email,
            $form->password
        );

        $this->transactionManager->wrap(function () use ($user, $form) {
            $user->save();
            $this->roleManager->assign($user->id, $form->role);
        });

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

        $this->transactionManager->wrap(function () use ($user, $form) {
            $user->save();
            $this->roleManager->assign($user->id, $form->role);
        });
    }

    public function assignRole($id, $role)
    {
        $this->roleManager->assign($this->getUserById($id)->id, $role);
    }

    public function remove($id)
    {
        $this->getUserById($id)->delete();
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