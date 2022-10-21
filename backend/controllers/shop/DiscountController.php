<?php

namespace backend\controllers\shop;

use backend\forms\shop\DiscountSearch;
use shop\entities\shop\Discount;
use shop\forms\manage\shop\DiscountForm;
use shop\useCases\manage\shop\DiscountManageService;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class DiscountController extends Controller
{
    private $discountManageService;

    public function __construct($id, $module, DiscountManageService $discountManageService, $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->discountManageService = $discountManageService;
    }

    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                    'activate' => ['POST'],
                    'draft' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new DiscountSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'discount' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $form = new DiscountForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $method = $this->discountManageService->create($form);

                return $this->redirect(['view', 'id' => $method->id]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('create', [
            'model' => $form,
        ]);
    }

    public function actionUpdate($id)
    {
        $discount = $this->findModel($id);

        $form = new DiscountForm($discount);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->discountManageService->edit($discount->id, $form);

                return $this->redirect(['view', 'id' => $discount->id]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $form,
            'discount' => $discount,
        ]);
    }

    public function actionDelete($id)
    {
        try {
            $this->discountManageService->remove($id);
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(['index']);
    }

    public function actionActivate($id)
    {
        try {
            $this->discountManageService->activate($id);
        } catch(\DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(['view', 'id' => $id]);
    }

    public function actionDraft($id)
    {
        try {
            $this->discountManageService->draft($id);
        } catch(\DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(['view', 'id' => $id]);
    }

    protected function findModel($id) : Discount
    {
        if (($model = Discount::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}