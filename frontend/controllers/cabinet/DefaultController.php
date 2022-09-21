<?php

namespace frontend\controllers\cabinet;

use yii\filters\AccessControl;
use yii\web\Controller;

class DefaultController extends Controller
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionCabinet() : string
    {
        return $this->render('cabinet');
    }
}