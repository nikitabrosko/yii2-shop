<?php

namespace backend\controllers;

use shop\forms\auth\LoginForm;
use shop\services\auth\LoginService;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;

class AuthController extends Controller
{
    private $loginService;

    public function __construct($id, $module, LoginService $loginService, $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->loginService = $loginService;
    }

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout = 'main-login';

        $form = new LoginForm();

        if ($form->load(Yii::$app->request->post()) && $this->loginService->login($form)) {
            return $this->goBack();
        }

        $form->password = '';

        return $this->render('login', [
            'model' => $form,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}