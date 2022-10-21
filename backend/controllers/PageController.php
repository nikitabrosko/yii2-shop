<?php

namespace backend\controllers;

use backend\forms\PageSearch;
use shop\entities\Page;
use shop\forms\manage\PageForm;
use shop\useCases\manage\PageManageService;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class PageController extends Controller
{
    private $pageManageService;

    public function __construct($id, $module, PageManageService $pageManageService, $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->pageManageService = $pageManageService;
    }

    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new PageSearch();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'page' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $form = new PageForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $page = $this->pageManageService->create($form);

                return $this->redirect(['view', 'id' => $page->id]);
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
        $page = $this->findModel($id);

        $form = new PageForm($page);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->pageManageService->edit($page->id, $form);

                return $this->redirect(['view', 'id' => $page->id]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $form,
            'page' => $page,
        ]);
    }

    public function actionDelete($id)
    {
        try {
            $this->pageManageService->remove($id);
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(['index']);
    }

    public function actionMoveUp($id)
    {
        $this->pageManageService->moveUp($id);

        return $this->redirect(['index']);
    }

    public function actionMoveDown($id)
    {
        $this->pageManageService->moveDown($id);

        return $this->redirect(['index']);
    }

    protected function findModel($id): Page
    {
        if (($model = Page::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}