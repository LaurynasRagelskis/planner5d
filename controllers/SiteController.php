<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\ProjectFile;
use app\models\UploadForm;
use app\models\Plan;
use yii\web\UploadedFile;


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

            //tikrinu ar i6 failo
            if( $formModel->file = UploadedFile::getInstance($formModel, 'file') ) {
                if($formModel->file->extension != 'p5d')
                    $formModel->addError('file', 'Wrong JSON file extension. ');
                else {
                    $formModel->content = file_get_contents($formModel->file->tempName);
                }
            }
            else if ( $formModel->url && $formModel->validate(['url', 'name', 'description'])) {
                $ch = curl_init( $formModel->url );
                curl_setopt_array( $ch, [
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_HTTPHEADER => ['Content-type: application/json']
                ]);
                $formModel->content = curl_exec($ch);
            }
            else if ( $formModel->json && $formModel->validate(['json']) ) {
                $formModel->content = $formModel->json;
            }

            if (!$formModel->hasErrors() && $formModel->validate(['content', 'name', 'description'])) {
                $objJson = json_decode($formModel->content);
                $model->setAttributes([
                    'name' => trim($formModel->name) ? : $objJson->name,
                    'content' => json_encode($objJson),
                    'description' => $formModel->description,
                ]);
                if($model->validate() && $model->save()) {
                    Yii::$app->session->setFlash('projectFileUploaded', true);
                    $formModel = new UploadForm();
                }
                else
                    Yii::$app->session->setFlash('projectFileUploadedError', true);
            }
        }
        $model = ProjectFile::find()->orderBy(['id'=>SORT_DESC])->all();
        return $this->render('index',[
            'model' => $model,
            'formModel' => $formModel
        ]);
    }

    public function actionProject($id)
    {
        //echo $id;
        //die(' stopas');
        $model = ProjectFile::findOne(['id' => $id]);
        $project = json_decode($model->content);
        $plan = new Plan(['data' => $project->data]);

        return $this->render('project', [
            'model' => $model,
            'project' => $project,
            'plan' => $plan,
        ]);
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
}
