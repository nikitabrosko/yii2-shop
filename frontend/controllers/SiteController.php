<?php

namespace frontend\controllers;

use yii\web\Controller;

class SiteController extends Controller
{
    public function actions() : array
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex() : string
    {
        $this->layout = 'home';
        return $this->render('index');
    }
}
