<?php

namespace frontend\controllers;

use shop\forms\contact\ContactForm;
use shop\services\contact\ContactService;
use Yii;
use yii\web\Controller;

class ContactController extends Controller
{
    private $contactService;

    public function __construct($id, $module, ContactService $contactService, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->contactService = $contactService;
    }

    public function actions() : array
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionContact()
    {
        $form = new ContactForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $form->email = Yii::$app->params['adminEmail'];
                if ($this->contactService->send($form)) {
                    Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
                } else {
                    Yii::$app->session->setFlash('error', 'There was an error sending your message.');
                }
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }

            return $this->refresh();
        }

        return $this->render('contact', [
            'model' => $form,
        ]);
    }
}