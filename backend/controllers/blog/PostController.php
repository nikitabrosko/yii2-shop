<?php

namespace backend\controllers\blog;

use shop\forms\manage\blog\post\PostForm;
use shop\useCases\manage\blog\postManageService;
use Yii;
use shop\entities\blog\Post\Post;
use backend\forms\blog\PostSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class PostController extends Controller
{
    private $postManageService;

    public function __construct($id, $module, \shop\useCases\manage\blog\PostManageService $postManageService, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->postManageService = $postManageService;
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
                    'delete-photo' => ['POST'],
                    'move-photo-up' => ['POST'],
                    'move-photo-down' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new PostSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'post' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $form = new PostForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $post = $this->postManageService->create($form);

                return $this->redirect(['view', 'id' => $post->id]);
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
        $post = $this->findModel($id);

        $form = new PostForm($post);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->postManageService->edit($post->id, $form);

                return $this->redirect(['view', 'id' => $post->id]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $form,
            'post' => $post,
        ]);
    }

    public function actionDelete($id)
    {
        try {
            $this->postManageService->remove($id);
        } catch (\DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(['index']);
    }

    public function actionActivate($id)
    {
        try {
            $this->postManageService->activate($id);
        } catch (\DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(['view', 'id' => $id]);
    }

    public function actionDraft($id)
    {
        try {
            $this->postManageService->draft($id);
        } catch (\DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(['view', 'id' => $id]);
    }

    protected function findModel($id): Post
    {
        if (($model = Post::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}