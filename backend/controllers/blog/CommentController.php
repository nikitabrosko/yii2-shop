<?php

namespace backend\controllers\blog;

use backend\forms\blog\CommentSearch;
use shop\forms\manage\blog\post\CommentEditForm;
use shop\services\manage\blog\CommentManageService;
use Yii;
use shop\entities\blog\post\Post;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class CommentController extends Controller
{
    private $commentManageService;

    public function __construct($id, $module, CommentManageService $commentManageService, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->commentManageService = $commentManageService;
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
        $searchModel = new CommentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionUpdate($post_id, $id)
    {
        $post = $this->findModel($post_id);
        $comment = $post->getComment($id);

        $form = new CommentEditForm($comment);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->commentManageService->edit($post->id, $comment->id, $form);

                return $this->redirect(['view', 'post_id' => $post->id, 'id' => $comment->id]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('update', [
            'post' => $post,
            'model' => $form,
            'comment' => $comment,
        ]);
    }

    public function actionView($post_id, $id)
    {
        $post = $this->findModel($post_id);
        $comment = $post->getComment($id);

        return $this->render('view', [
            'post' => $post,
            'comment' => $comment,
        ]);
    }

    public function actionActivate($post_id, $id)
    {
        $post = $this->findModel($post_id);

        try {
            $this->commentManageService->activate($post->id, $id);
        } catch (\DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(['view', 'post_id' => $post_id, 'id' => $id]);
    }

    public function actionDelete($post_id, $id)
    {
        $post = $this->findModel($post_id);

        try {
            $this->commentManageService->remove($post->id, $id);
        } catch (\DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(['index']);
    }

    protected function findModel($id): Post
    {
        if (($model = Post::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}