<?php

namespace backend\controllers\shop;

use shop\entities\shop\product\Modification;
use shop\entities\shop\product\Product;
use shop\forms\manage\shop\product\ModificationForm;
use shop\services\manage\shop\ProductManageService;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class ModificationController extends Controller
{
    private $productManageService;

    public function __construct($id, $module, ProductManageService $productManageService, $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->productManageService = $productManageService;
    }

    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    public function actionIndex()
    {
        return $this->redirect('shop/product');
    }

    public function actionCreate($product_id)
    {
        $product = Product::findOne(['id' => $product_id]);

        $form = new ModificationForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->productManageService->addModification($product->id, $form);

                return $this->redirect(['shop/product/view', 'id' => $product->id, '#' => 'modifications']);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('create', [
            'product' => $product,
            'model' => $form,
        ]);
    }

    public function actionUpdate($product_id, $id)
    {
        $product = Product::findOne(['id' => $product_id]);
        $modification = $product->getModification($id);

        $form = new ModificationForm($modification);
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->productManageService->editModification($product->id, $modification->id, $form);

                return $this->redirect(['shop/product/view', 'id' => $product->id, '#' => 'modifications']);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('update', [
            'product' => $product,
            'model' => $form,
            'modification' => $modification,
        ]);
    }

    /**
     * Deletes an existing Modification model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($product_id, $id)
    {
        $product = Product::findOne(['id' => $product_id]);

        try {
            $this->productManageService->removeModification($product->id, $id);
        } catch (\DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(['shop/product/view', 'id' => $product->id, '#' => 'modifications']);
    }

    /**
     * Finds the Modification model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return Modification the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Modification::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
