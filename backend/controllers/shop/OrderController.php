<?php

namespace backend\controllers\shop;

use backend\forms\shop\OrderSearch;
use shop\entities\shop\order\Order;
use shop\forms\manage\shop\order\OrderEditForm;
use shop\useCases\manage\shop\OrderManageService;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class OrderController extends Controller
{
    private $orderManageService;

    public function __construct($id, $module, \shop\useCases\manage\shop\OrderManageService $orderManageService, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->orderManageService = $orderManageService;
    }

    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'export' => ['POST'],
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'order' => $this->findModel($id),
        ]);
    }

    public function actionUpdate($id)
    {
        $order = $this->findModel($id);

        $form = new OrderEditForm($order);
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->orderManageService->edit($order->id, $form);

                return $this->redirect(['view', 'id' => $order->id]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $form,
            'order' => $order,
        ]);
    }

    public function actionDelete($id)
    {
        try {
            $this->orderManageService->remove($id);
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(['index']);
    }

    protected function findModel($id): Order
    {
        if (($model = Order::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
