<?php

namespace backend\controllers\blog;

use backend\forms\blog\TagSearch;
use shop\entities\blog\Tag;
use shop\forms\manage\blog\TagForm;
use shop\useCases\manage\blog\TagManageService;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class TagController extends Controller
{
    private $tagManageService;

    public function __construct($id, $module, TagManageService $tagManageService, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->tagManageService = $tagManageService;
    }

    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new TagSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'tag' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $form = new TagForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $tag = $this->tagManageService->create($form);

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

    public function actionUpdate($id)
    {
        $tag = $this->findModel($id);

        $form = new TagForm($tag);
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->tagManageService->edit($tag->id, $form);

                return $this->redirect(['view', 'id' => $tag->id]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $form,
            'tag' => $tag,
        ]);
    }

    public function actionDelete($id)
    {
        try {
            $this->tagManageService->remove($id);
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(['index']);
    }

    protected function findModel($id): Tag
    {
        if (($model = Tag::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}