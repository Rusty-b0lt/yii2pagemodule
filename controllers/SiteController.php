<?php

namespace app\controllers;

use app\models\Category;
use app\models\Rating;
use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Page;
use yii\helpers\ArrayHelper;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
    public function actionPage()
    {

        $name = Yii::$app->request->get('name');
        $model = Page::find(['slug' => $name])->one();

        if($model !== null) {
            $pageTags = ArrayHelper::toArray($model->tags, [
                'app\models\Tag' => [
                    'tag',
                ],
            ]);
            return $this->render('page', [
                'model' => $model,
                'pageTags' => $pageTags,
            ]);
        }
        else {
            throw new NotFoundHttpException();
        }
    }
    public function actionCategory()
    {
        $catname = Yii::$app->request->get('catname');
        $model = Category::findOne(['slug' => $catname]);
        if($model !== null) {

            // '101' => 'Normal', '110' => 'Unlisted', '111' => 'Admin', '100' => 'Authorized'

            if (Yii::$app->user->isGuest) {
                $children = Category::findAll(['parent_id' => $model->category_id, 'access' => 101]);
                $query = Page::find()->where(['category' => $model->slug, 'access' => 101]);
            }
            elseif (Yii::$app->user->identity->username === 'admin') {
                $children = Category::find()->where(['parent_id' => $model->category_id])->andWhere(['<>', 'access', 110])->all();
                $query = Page::find()->where(['category' => $model->slug])->andWhere(['<>', 'access', 110]);
            }
            else {
                $children = Category::findAll(['parent_id' => $model->category_id, 'access' => [100, 101]]);
                $query = Page::find()->where(['category' => $model->slug, 'access' => [100, 101]]);
            }
            return $this->render('category', [
                'model' => $model,
                'children' => $children,
                'query' => $query,
            ]);
        }
        else {
            throw new NotFoundHttpException();
        }

    }
    public function actionRating()
    {
        $rating = Yii::$app->request->get('rating');
        $user = Yii::$app->user->identity->username;
        $split = preg_split('/\//', Yii::$app->request->get('page'));
        $pageName = $split[3];
        if($rating !== null && $user !== null && $pageName !== null && $rating <=5)  {
            $entry = new Rating();
            $entry->user = Yii::$app->user->identity->username;
            $entry->page = $pageName;
            $entry->rating = $rating;
            $entry->save();

            $page = Page::findOne(['slug' => $pageName]);
            $ratings = Rating::findAll(['page' => $pageName]);
            $sum = 0;
            foreach($ratings as $r) {
                $sum += $r['rating'];
            }
            $average = round($sum / count($ratings));
            $page->rating = $average;
            $page->save();
        }
    }
}
