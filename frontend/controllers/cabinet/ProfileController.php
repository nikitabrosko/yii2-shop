<?php

namespace frontend\controllers\cabinet;

use shop\forms\user\ProfileEditForm;
use shop\useCases\cabinet\ProfileService;
use Yii;
use shop\entities\user\User;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class ProfileController extends Controller
{
    private $profileService;

    public function __construct($id, $module, ProfileService $profileService, $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->profileService = $profileService;
    }

    public function actionEdit()
    {
        $user = $this->findModel(Yii::$app->user->id);

        $form = new ProfileEditForm($user);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->profileService->edit($user->id, $form);

                return $this->redirect(['/cabinet/default/cabinet', 'id' => $user->id]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('edit', [
            'model' => $form,
            'user' => $user,
        ]);
    }

    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
