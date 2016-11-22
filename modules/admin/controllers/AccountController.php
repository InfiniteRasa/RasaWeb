<?php

namespace app\modules\admin\controllers;

use app\models\db\Account;

use Yii;

use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Class UserController
 * implements the CRUD actions for User model.
 *
 * @package app\modules\admin\controllers
 */
class AccountController extends Controller
{
    /**
     * @inheritdoc
     */
    public $defaultAction = 'list';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => [ 'post' ],
                ],
            ],
        ];
    }

    /**
     * Display error.
     *
     * @return string
     */
    public function actionError()
    {
        if (($exception = Yii::$app->getErrorHandler()->exception) === null) {
            // action has been invoked not from error handler, but by direct route, so we display '404 Not Found'
            $exception = new HttpException(404, Yii::t('yii', 'Page not found.'));
        }

        return $this->render('error', [
            'exception' => $exception,
        ]);
    }

    /**
     * Lists the Users.
     *
     * @return string
     */
    public function actionList()
    {
        $provider = new ActiveDataProvider([
            'query' => Account::find(),
            'sort' => [
                'attributes' => [ 'email', 'username', 'level', 'last_login', 'locked' ]
            ],
            'pagination' => [ 'pageSize' => 20 ]
        ]);

        return $this->render('list', [ 'dataProvider' => $provider ]);
    }

    /**
     *  Handles the view and update of an Account.
     *
     * @param integer|null $id
     * @param boolean|false $view
     * @return array|string|Response
     * @throws \Exception
     */
    public function actionForm($id = null, $view = false)
    {
        /* @var \app\models\db\Account $model */
        $model = is_null($id) ? null : Account::findOne([ 'id' => $id ]);
        if (is_null($model)) {
            return $this->redirect([ 'list' ]);
        }

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }

            if ($model->update()) {
                return $this->redirect([ 'account/list' ]);
            } else {
                Yii::error('Unable to save Account! Errors: ' . print_r($model->getErrors(), true));
            }
        }

        return $this->render('form', [
            'disabled' => $view,
            'model' => $model
        ]);
    }

    /**
     * Locks/Unlocks an Account.
     *
     * @param integer|null $id
     * @return Response
     * @throws \Exception
     */
    public function actionLock($id = null)
    {
        /* @var \app\models\db\Account $model */
        $model = Account::findOne([ 'id' => $id ]);
        if (is_null($model) || $model->locked) {
            return $this->redirect([ 'list' ]);
        }

        $model->locked = $model->locked == 0 ? 1 : 0;
        if (!$model->update()) {
            Yii::error('Unable to update model! Error: ' . print_r($model->getErrors(), true));
        }

        return $this->redirect([ 'account/list' ]);
    }
}
