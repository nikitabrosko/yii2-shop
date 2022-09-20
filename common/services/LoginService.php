<?php

namespace common\services;

use common\entities\User;
use common\forms\LoginForm;
use common\exceptions\NotFoundException;
use Yii;

class LoginService
{
    public function validatePassword(LoginForm $form) : bool
    {
        if (!$form->hasErrors()) {
            try {
                $user = $this->getUser($form->username);

                if (!$user->validatePassword($form->password)) {
                    $form->addError('password', 'Incorrect password.');

                    return false;
                }
            } catch (NotFoundException $e) {
                $form->addError('username', 'Incorrect username.');

                return false;
            }
        }

        return true;
    }

    public function login(LoginForm $form) : bool
    {
        if ($form->validate() && $this->validatePassword($form)) {
            return Yii::$app->user->login($this->getUser($form->username), $form->rememberMe ? 3600 * 24 * 30 : 0);
        }

        return false;
    }

    protected function getUser(string $username) : ?User
    {
        $user = User::findByUsername($username);

        if (!$user) {
            throw new NotFoundException('User not found.');
        }

        return $user;
    }
}