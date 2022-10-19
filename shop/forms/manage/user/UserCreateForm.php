<?php

namespace shop\forms\manage\user;

use shop\entities\user\User;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class UserCreateForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $role;

    public function rolesList() : array
    {
        return ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'description');
    }

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