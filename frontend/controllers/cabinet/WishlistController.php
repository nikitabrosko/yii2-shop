<?php

namespace frontend\controllers\cabinet;

use shop\readModels\shop\ProductReadRepository;
use shop\useCases\cabinet\WishlistService;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

class WishlistController extends Controller
{
    public $layout = 'cabinet';

    private $wishlistService;
    private $products;

    public function __construct($id, $module, WishlistService $wishlistService,
                                ProductReadRepository $products, $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->wishlistService = $wishlistService;
        $this->products = $products;
    }

    public function behaviors() : array
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
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'add' => ['POST'],
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionWishlist()
    {
        $dataProvider = $this->products->getWishList(\Yii::$app->user->id);

        return $this->render('wishlist', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionAdd($id)
    {
        try {
            $this->wishlistService->add(Yii::$app->user->id, $id);

            Yii::$app->session->setFlash('success', 'Success!');
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(Yii::$app->request->referrer ?: ['wishlist']);
    }

    public function actionDelete($id)
    {
        try {
            $this->wishlistService->remove(Yii::$app->user->id, $id);
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(['wishlist']);
    }
}