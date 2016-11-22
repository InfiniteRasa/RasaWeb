<?php

namespace app\modules\admin\commands;

use app\models\db\Account;

use Yii;

use yii\console\Controller;
use yii\helpers\Console;

/**
 * Class UserController
 *
 * @package app\modules\admin\commands
 */
class AccountController extends Controller
{
    /**
     * @var string default action to run, when not supplying action parameter to the controller.
     */
    public $defaultAction = 'create';

    /**
     * Creates an Admin Account.
     */
    public function actionCreate()
    {
        $account = new Account();
        $account->username = $this->prompt(Yii::t('admin', 'console.prompt.username'));
        $account->email = $this->prompt(Yii::t('admin', 'console.prompt.email'));
        $account->password = $this->prompt(Yii::t('admin', 'console.prompt.password'));

        if (!$account->create(false)) {
            $this->stdout(Yii::t('admin', 'console.error.existing') . "\n", Console::FG_RED);
            return;
        }

        $this->stdout(Yii::t('admin', 'console.log.success', [ 'name' => $account->username ]) . "\n", Console::FG_GREEN);
    }
}
