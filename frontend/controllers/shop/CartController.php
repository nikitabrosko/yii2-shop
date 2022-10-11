<?php

namespace frontend\controllers\shop;

use shop\forms\shop\AddToCartForm;
use shop\readModels\shop\ProductReadRepository;
use shop\services\shop\CartService;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class CartController extends Controller
{
    public $layout = 'blank';

    private $products;
    private $cartService;

    public function __construct($id, $module, CartService $cartService,
                                ProductReadRepository $products, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->products = $products;
        $this->cartService = $cartService;
    }

    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'quantity' => ['POST'],
                    'remove' => ['POST'],
                ],
            ],
        ];
    }

    public function actionCart()
    {
        $cart = $this->cartService->getCart();

        return $this->render('cart', [
            'cart' => $cart,
        ]);
    }

    public function actionAdd($id)
    {
        if (!$product = $this->products->find($id)) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        if (!$product->modifications) {
            try {
                $this->cartService->add($product->id, null, 1);
                Yii::$app->session->setFlash('success', 'Success!');

                return $this->redirect(Yii::$app->request->referrer);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        $form = new AddToCartForm($product);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->cartService->add($product->id, $form->modification, $form->quantity);

                return $this->redirect(['cart']);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('add', [
            'product' => $product,
            'model' => $form,
        ]);
    }

    public function actionQuantity($id)
    {
        try {
            $this->cartService->set($id, (int)Yii::$app->request->post('quantity'));
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(['cart']);
    }

    public function actionRemove($id)
    {
        try {
            $this->cartService->remove($id);
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(['cart']);
    }
}