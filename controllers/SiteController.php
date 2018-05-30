<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ProjectFile;
use app\models\UploadForm;
use app\models\Plan;
use yii\web\UploadedFile;
use yii\data\Pagination;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
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
                'class' => VerbFilter::className(),
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
        $formModel = new UploadForm();

        if ( $formModel->load( Yii::$app->request->post() )  ) {
            $model = new ProjectFile();

            //check form JSON data upload source
            if( $formModel->file = UploadedFile::getInstance($formModel, 'file') ) {
                if($formModel->file->extension != 'p5d')
                    $formModel->addError('file', 'Wrong JSON file extension. ');
                else {
                    $formModel->content = file_get_contents($formModel->file->tempName);
                }
            } else if ( $formModel->url && $formModel->validate(['url', 'name', 'description'])) {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, str_replace(' ', '%20', $formModel->url) );
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-type: application/json']);

                if(curl_exec($ch) === FALSE) {
                    $formModel->addError('url', curl_error($ch) );
                } else {
                    $formModel->content = curl_exec($ch);
                }
                curl_close($ch);

            } else if ( $formModel->json && $formModel->validate(['json']) ) {
                $formModel->content = $formModel->json;
            }

            if (!$formModel->hasErrors() && $formModel->validate(['content', 'name', 'description'])) {
                $objJson = json_decode($formModel->content);
                $plan = new Plan(['data' => $objJson->data]);
                $model->setAttributes([
                    'name' => trim($formModel->name) ? : $objJson->name,
                    'content' => json_encode($objJson),
                    'plan' => json_encode($plan),
                    'description' => $formModel->description,
                ]);
                if($model->validate() && $model->save()) {
                    Yii::$app->session->setFlash('alert', ['type' => 'success', 'msg' => 'Thank you for new project! Now you can preview project in 2D.']);
                    $formModel = new UploadForm();
                } else {
                    Yii::$app->session->setFlash('alert', ['type' => 'danger', 'msg' => 'Sorry, something went wrong. Refresh page and try again.']);
                }
            }
        }

        $query = ProjectFile::find(['id', 'name', 'timestamp', 'description']);

        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count()]);
        $pages->setPageSize(5);
        $model = $query->offset($pages->offset)
            ->orderBy(['id'=>SORT_DESC])
            ->limit($pages->limit)
            ->all();

        return $this->render('index',[
            'model' => $model,
            'formModel' => $formModel,
            'pages' => $pages
        ]);
    }

    /**
     * Displays page with rendered plan.
     *
     * @param integer $id
     * @return string
     */
    public function actionProject($id)
    {
        $model = ProjectFile::find()
            ->where(['id' => $id])
            ->select(['id', 'name', 'plan', 'timestamp', 'description', 'content'])
            ->one();

        $model->plan = json_decode($model->plan);
        return $this->render('project', [
            'model' => $model
        ]);
    }

    /**
     * Delete project file.
     *
     * @param integer $id
     * @return Response|string
     * @throws
     */
    public function actionDelete($id)
    {
        if(Yii::$app->user->id == 100 && $model = ProjectFile::findOne(['id' => $id])) {
            if($model->delete())
                Yii::$app->session->setFlash('alert', ['type' => 'warning', 'msg' => 'You deleted project file ID #'.$id]);
            else
                Yii::$app->session->setFlash('alert', ['type' => 'dange', 'msg' => 'Error! File ID #'.$id . ' not deleted.']);
        }
        return $this->goHome();
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
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
