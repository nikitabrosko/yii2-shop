<?php

namespace frontend\controllers\shop;

use shop\cart\Cart;
use shop\forms\shop\order\OrderForm;
use shop\services\shop\OrderService;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

class CheckoutController extends Controller
{
    public $layout = 'blank';

    private $orderService;
    private $cart;

    public function __construct($id, $module, OrderService $orderService, Cart $cart, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->orderService = $orderService;
        $this->cart = $cart;
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

    public function actionCheckout()
    {
        $form = new OrderForm($this->cart->getWeight());

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $order = $this->orderService->checkout(Yii::$app->user->id, $form);

                return $this->redirect(['/cabinet/order/view', 'id' => $order->id]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('checkout', [
            'cart' => $this->cart,
            'model' => $form,
        ]);
    }
}