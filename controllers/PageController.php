<?php

namespace app\controllers;

use app\models\Category;
use Yii;
use app\models\Page;
use app\models\PageSearch;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Tag;
use yii\filters\AccessControl;

/**
 * PageController implements the CRUD actions for Page model.
 */
class PageController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'view', 'create', 'update', 'delete'],
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function($rule, $action) {
                            $bool = Yii::$app->user->identity->username === 'admin';
                            return $bool;
                        }
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Page models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Page model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Page model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Page();

        $model->creation_date = date('Y-m-d');
        $model->mod_date = date('Y-m-d');
        $model->rating = 0;

        $categories = ArrayHelper::map(Category::find()->all(), 'slug', 'name');
        $tagList = Yii::$app->request->post('tags');
        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            if($tagList !== null && is_array($tagList)) {
                foreach ($tagList as $tagName) {
                    $existingTag = Tag::findOne(['tag' => $tagName]);
                    if ($existingTag === null) {
                        $tag = new Tag();
                        $tag->tag = $tagName;
                        $tag->save();

                    } else {
                        $tag = $existingTag;
                    }
                    $model->link('tags', $tag);
                }
            }

            return $this->redirect(['view', 'id' => $model->id]);
        }


        return $this->render('create', [
            'model' => $model,
            'categories' => $categories,
        ]);
    }

    /**
     * Updates an existing Page model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $model->mod_date = date('Y-m-d');

        $tagList = Yii::$app->request->post('tags');
        $categories = ArrayHelper::map(Category::find()->all(), 'slug', 'name');
        $pageTags = ArrayHelper::toArray($model->tags, [
            'app\models\Tag' => [
                'tag',
            ],
        ]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if($tagList !== null && is_array($tagList)) {
                $model->unlinkAll('tags', true);
                foreach ($tagList as $tagName) {
                    $existingTag = Tag::findOne(['tag' => $tagName]);
                    if ($existingTag === null) {
                        $tag = new Tag();
                        $tag->tag = $tagName;
                        $tag->save();

                    } else {
                        $tag = $existingTag;
                    }
                    $model->link('tags', $tag);
                }
            }
            elseif ($tagList === null) {
                $model->unlinkAll('tags', true);
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'categories' => $categories,
            'pageTags' => $pageTags,
        ]);
    }

    /**
     * Deletes an existing Page model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Page model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Page the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Page::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
