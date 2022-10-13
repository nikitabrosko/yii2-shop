<?php

namespace frontend\controllers\cabinet;

use shop\readModels\shop\OrderReadRepository;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class OrderController extends Controller
{
    public $layout = 'cabinet';
    private $orders;

    public function __construct($id, $module, OrderReadRepository $orders, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->orders = $orders;
    }

    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionOrders()
    {
        $dataProvider = $this->orders->getOwn(\Yii::$app->user->id);

        return $this->render('orders', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        if (!$order = $this->orders->findOwn(\Yii::$app->user->id, $id)) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        return $this->render('view', [
            'order' => $order,
        ]);
    }
}