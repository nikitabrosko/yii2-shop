<?php

namespace api\controllers\user;

use api\helpers\DateHelper;
use shop\entities\user\User;
use shop\helpers\UserHelper;
use yii\rest\Controller;

class ProfileController extends Controller
{
    public function actionIndex() : array
    {
        return $this->serializeUser($this->findModel());
    }

    protected function verbs() : array
    {
        return [
            'index' => ['get'],
        ];
    }

    private function findModel() : User
    {
        return User::findOne(\Yii::$app->user->id);
    }

    private function serializeUser(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->username,
            'email' => $user->email,
            'date' => [
                'created' => DateHelper::formatApi($user->created_at),
                'updated' => DateHelper::formatApi($user->updated_at),
            ],
            'status' => [
                'code' => $user->status,
                'name' => UserHelper::statusName($user->status),
            ],
        ];
    }
}