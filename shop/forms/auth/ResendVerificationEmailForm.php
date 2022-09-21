<?php

namespace shop\forms\auth;

use Yii;
use shop\entities\user\User;
use yii\base\Model;

class ResendVerificationEmailForm extends Model
{
    /**
     * @var string
     */
    public $email;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => '\shop\entities\user\User',
                'filter' => ['status' => User::STATUS_INACTIVE],
                'message' => 'There is no user with this email address.'
            ],
        ];
    }
}
