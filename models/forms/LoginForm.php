<?php

namespace app\models\forms;

use app\models\db\Account;

use Yii;

use yii\base\Model;

class LoginForm extends Model
{
    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $password;

    /**
     * @var bool
     */
    public $rememberMe = true;

    /**
     * @var \app\models\db\Account|null|bool
     */
    private $_account = false;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'password'], 'required'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
            ['email', 'validateEmail']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email'      => Yii::t('app', 'login.form.email'),
            'password'   => Yii::t('app', 'login.form.password'),
            'rememberMe' => Yii::t('app', 'login.form.rememberMe')
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $account = $this->getAccount();

            if (is_null($account) || !$account->validatePassword($this->password)) {
                $this->addError($attribute, Yii::t('app', 'login.form.error.email_or_password'));
            }
        }
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateEmail($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $account = $this->getAccount();

            if (!is_null($account) && !$account->validated) {
                $this->addError($attribute, Yii::t('app', 'login.form.error.email_validation'));
            }
        }
    }

    /**
     * Logs in an account using the provided username and password.
     * @return boolean whether the account is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            if (($success = Yii::$app->user->login($this->getAccount(), $this->rememberMe ? 30 * 86400 : 0))) {
                $this->getAccount()->last_login = time();
                $this->getAccount()->update();
            }

            return $success;
        } else {
            Yii::error('Unable to login Account! Errors: ' . print_r($this->errors, true));
        }

        return false;
    }
    /**
     * Finds account by email
     *
     * @return Account|null
     */
    public function getAccount()
    {
        if ($this->_account === false) {
            $this->_account = Account::findByEmail($this->email);
        }

        return $this->_account ?: null;
    }
}
