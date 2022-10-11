<?php

namespace frontend\controllers\auth;

use shop\forms\auth\PasswordResetRequestForm;
use shop\forms\auth\ResetPasswordForm;
use shop\services\auth\PasswordResetService;
use Yii;
use yii\base\InvalidArgumentException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

class ResetController extends Controller
{
    public $layout = 'cabinet';

    private $passwordResetService;

    public function __construct($id, $module, PasswordResetService $passwordResetService, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->passwordResetService = $passwordResetService;
    }

    public function actions() : array
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionRequest()
    {
        $form = new PasswordResetRequestForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                if ($this->passwordResetService->requestPasswordReset($form)) {
                    Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                    return $this->goHome();
                }

                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $form,
        ]);
    }

    public function actionReset(string $token)
    {
        $form = new ResetPasswordForm();

        try {
            if ($form->load(Yii::$app->request->post())
                && $form->validate()
                && $this->passwordResetService->resetPassword($token, $form)) {
                Yii::$app->session->setFlash('success', 'New password saved.');

                return $this->goHome();
            }
        } catch (InvalidArgumentException $e) {
            Yii::$app->errorHandler->logException($e);
            throw new BadRequestHttpException($e->getMessage());
        }

        return $this->render('resetPassword', [
            'model' => $form,
        ]);
    }
}