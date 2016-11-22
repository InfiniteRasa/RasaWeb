<?php

namespace app\controllers;

use app\models\db\Account;
use app\models\forms\LoginForm;

use Yii;

use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => [ 'login', 'logout', 'register' ],
                'rules' => [
                    [
                        'actions' => [ 'logout' ],
                        'allow' => true,
                        'roles' => [ '@' ]
                    ],
                    [
                        'actions' => [ 'login', 'register' ],
                        'allow' => true,
                        'roles' => [ '?' ]
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'login' => [ 'get', 'post' ],
                    'logout' => [ 'post' ]
                ]
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction'
            ]
        ];
    }

    /**
     * Renders an index page, advertising our service.
     *
     * @return string|Response
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Renders the login view on GET, and logs in the Account on POST.
     *
     * @return Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect([ 'site/index' ]);
        }

        $model = new LoginForm();

        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->login()) {
                return $this->redirect([ 'index' ]);
            } else {
                Yii::error('Unable to login Account! Errors: ' . print_r($model->errors, true));
            }
        }

        return $this->render('login', [
            'model' => $model
        ]);
    }

    /**
     * Logs the current user out, and redirects it to the index.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->redirect([ 'site/index' ]);
    }

    /**
     * Handles registration.
     *
     * @return array|Response
     */
    public function actionRegister()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect([ 'index' ]);
        }

        $model = new Account();

        if (Yii::$app->request->isPost) {
            if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }

            if ($model->load(Yii::$app->request->post()) && $model->create()) {
                return $this->redirect([ 'site/registered' ]);
            }
        }

        return $this->render('register', [
            'model' => $model
        ]);
    }

    /**
     * Renders the registered page.
     *
     * @return array|Response
     */
    public function actionRegistered()
    {
        return $this->render('registered');
    }

    /**
     * Handles validation of a registered user.
     *
     * @param $token string
     * @return array|Response
     */
    public function actionValidate($token)
    {
        if ($token == null) {
            return $this->redirect([ 'index' ]);
        }

        /* @var \app\models\db\Account $model */
        $model = Account::findByToken($token);
        if ($model == null) {
            return $this->redirect([ 'site/index' ]);
        }

        $model->validated = true;
        $model->validation_token = null;
        $model->save();

        return $this->render('registered');
    }
}
