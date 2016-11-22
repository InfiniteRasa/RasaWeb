<?php

namespace app\models\db;

use Yii;

use yii\db\ActiveRecord;
use yii\db\IntegrityException;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "account".
 *
 * @property string $id
 * @property string $email
 * @property string $username
 * @property string $password
 * @property string $salt
 * @property integer $level
 * @property string $last_ip
 * @property integer $last_server_id
 * @property string $last_login
 * @property string $join_date
 * @property boolean $locked
 * @property boolean $validated
 * @property string $validation_token
 */
class Account extends ActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'account';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'username', 'password', 'salt', 'last_server_id', 'join_date'], 'required'],
            [['level', 'last_server_id'], 'integer'],
            [['last_login', 'join_date'], 'safe'],
            [['locked', 'validated'], 'boolean'],
            ['username', 'string', 'max' => 45],
            [['salt', 'validation_token'], 'string', 'max' => 40],
            ['email', 'string', 'max' => 120],
            ['password', 'string', 'max' => 64],
            ['last_ip', 'string', 'max' => 15],
            ['email', 'unique'],
            ['username', 'unique'],
            ['password', 'validatePassword']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'               => Yii::t('app', 'db.account.id'),
            'email'            => Yii::t('app', 'db.account.email'),
            'username'         => Yii::t('app', 'db.account.username'),
            'password'         => Yii::t('app', 'db.account.password'),
            'salt'             => Yii::t('app', 'db.account.salt'),
            'level'            => Yii::t('app', 'db.account.level'),
            'last_ip'          => Yii::t('app', 'db.account.last_ip'),
            'last_server_id'   => Yii::t('app', 'db.account.last_server_id'),
            'last_login'       => Yii::t('app', 'db.account.last_login'),
            'join_date'        => Yii::t('app', 'db.account.join_date'),
            'locked'           => Yii::t('app', 'db.account.locked'),
            'validated'        => Yii::t('app', 'db.account.validated'),
            'validation_token' => Yii::t('app', 'db.account.validation_token'),
        ];
    }

    /**
     * Finds an identity by the given ID.
     * @param string|integer $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
        return static::findOne([ 'id' => $id ]);
    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|integer an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
        return null;
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return boolean whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        return false;
    }

    /**
     * Returns a User by his/her username.
     *
     * @param string $email
     * @return \app\models\db\Account
     */
    public static function findByEmail($email)
    {
        return static::findOne([ 'email' => $email ]);
    }

    /**
     * Returns a User by his/her validation token.
     *
     * @param string $token
     * @return \app\models\db\Account
     */
    public static function findByToken($token)
    {
        return static::findOne([ 'validation_token' => $token ]);
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password == hash("sha256", "{$this->salt}:{$password}");
    }

    /**
     * Creates a new User.
     *
     * @param bool $byUser
     * @return bool true,
     * @throws \yii\base\Exception
     */
    public function create($byUser = true)
    {
        $this->salt = hash("sha1", uniqid());
        $this->password = hash("sha256", "{$this->salt}:{$this->password}");
        $this->last_server_id = 0;
        $this->join_date = time();
        $this->locked = 0;
        $this->validated = 0;
        $this->validation_token = hash("sha1", uniqid());

        if ($byUser) {
            $this->last_ip = Yii::$app->request->userIP;
            $this->level = 0;
        }

        try {
            if (!$this->save()) {
                Yii::error('Unable to save Account! Error: ' . print_r($this->getErrors(), true));
                return false;
            }

            return true;
        } catch (IntegrityException $ie) {
            return false;
        }
    }
}
