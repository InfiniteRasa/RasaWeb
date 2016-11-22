<?php

namespace app\modules\admin;

use Yii;

use yii\base\Module as BaseModule;
use yii\console\Application as ConsoleApplication;
use yii\web\Application as WebApplication;

/**
 * Class Module
 *
 * @package app\modules\admin
 */
class Module extends BaseModule
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\admin\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (Yii::$app instanceof ConsoleApplication) {
            $this->controllerNamespace = 'app\modules\admin\commands';
        } else {
            Yii::$app->errorHandler->errorAction = 'admin/user/error';
        }
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if (Yii::$app instanceof WebApplication) {
            /* @var \app\models\db\Account $account */
            $account = Yii::$app->user->identity;

            if (is_null($account) || $account->level < 10) {
                return $action->controller->redirect([ '/site/index' ]);
            }
        }

        if (parent::beforeAction($action)) {
            $action->controller->layout = 'admin';

            return true;
        }

        return false;
    }
}
