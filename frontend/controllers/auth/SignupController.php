<?php

namespace frontend\controllers\auth;

use shop\forms\auth\ResendVerificationEmailForm;
use shop\forms\auth\SignupForm;
use shop\services\auth\SignupService;
use Yii;
use yii\base\InvalidArgumentException;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

class SignupController extends Controller
{
    private $signupService;

    public function __construct($id, $module, SignupService $signupService, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->signupService = $signupService;
    }

    public function behaviors() : array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ],
        ];
    }

    public function actions() : array
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionSignup()
    {
        $form = new SignupForm();

        try {
            if ($form->load(Yii::$app->request->post())
                && $form->validate()
                && $this->signupService->signup($form)) {
                Yii::$app->session->setFlash('success', 'Thank you for registration. Please check your inbox for verification email.');
                return $this->goHome();
            }
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->render('signup', [
            'model' => $form,
        ]);
    }

    public function actionVerifyEmail(string $token) : yii\web\Response
    {
        try {
            if (($user = $this->signupService->verifyEmail($token)) && Yii::$app->user->login($user)) {
                Yii::$app->session->setFlash('success', 'Your email has been confirmed!');
                return $this->goHome();
            }
        } catch (InvalidArgumentException|\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            throw new BadRequestHttpException($e->getMessage());
        }

        Yii::$app->session->setFlash('error', 'Sorry, we are unable to verify your account with provided token.');

        return $this->goHome();
    }

    public function actionResendVerificationEmail()
    {
        $form = new ResendVerificationEmailForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                if ($this->signupService->resendVerificationEmail($form)) {
                    Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                    return $this->goHome();
                }

                Yii::$app->session->setFlash('error', 'Sorry, we are unable to resend verification email for the provided email address.');
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('resendVerificationEmail', [
            'model' => $form
        ]);
    }
}