<?php

namespace shop\forms\manage\user;

use shop\entities\user\User;
use yii\base\Model;

class UserCreateForm extends Model
{
    public $username;
    public $email;
    public $password;

    public function rules() : array
    {
        return [
            [['username', 'email', 'password'], 'required'],
            ['email', 'email'],
            [['username', 'email', 'password'], 'string', 'max' => 255],
            [['username', 'email'], 'unique', 'targetClass' => User::class],
            ['password', 'string', 'min' => 6],
        ];
    }
}