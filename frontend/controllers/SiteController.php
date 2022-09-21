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
        return $this->render('index');
    }

    public function actionAbout() : string
    {
        return $this->render('about');
    }
}
