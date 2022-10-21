<?php

namespace backend\controllers\shop;

use shop\entities\shop\Characteristic;
use backend\forms\shop\CharacteristicSearch;
use shop\forms\manage\shop\CharacteristicForm;
use shop\useCases\manage\shop\CharacteristicManageService;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CharacteristicController implements the CRUD actions for Characteristic model.
 */
class CharacteristicController extends Controller
{
    private $characteristicManageService;

    public function __construct($id, $module, CharacteristicManageService $characteristicManageService, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->characteristicManageService = $characteristicManageService;
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

    /**
     * Lists all Characteristic models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new CharacteristicSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Characteristic model.
     * @param int $id
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Characteristic model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $form = new CharacteristicForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $tag = $this->characteristicManageService->create($form);

                return $this->redirect(['view', 'id' => $tag->id]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('create', [
            'model' => $form,
        ]);
    }

    /**
     * Updates an existing Characteristic model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $characteristic = $this->findModel($id);

        $form = new CharacteristicForm($characteristic);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->characteristicManageService->edit($characteristic->id, $form);

                return $this->redirect(['view', 'id' => $characteristic->id]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $form,
            'characteristic' => $characteristic,
        ]);
    }

    /**
     * Deletes an existing Characteristic model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        try {
            $this->characteristicManageService->remove($id);
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the Characteristic model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return Characteristic the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Characteristic::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
