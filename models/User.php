<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string|null $password_reset_token
 * @property string $access_token
 * @property string|null $type
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    const STATUS_ACTIVE = 10;
    const STATUS_DISABLED = 20;

    public $password;
    public $password_repeat;

    protected $_require_password = false;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        $rules =
        [
            [['username', 'password_hash', 'auth_key'], 'required'],
            [['type', 'password_repeat'], 'string'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['username', 'password_hash', 'password_reset_token'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['password_reset_token'], 'unique'],
            ['password', 'string', 'min' => 8],
            ['password_repeat', 'compare', 'compareAttribute' => 'password', 'message' => "Passwords don't match" ],
        ];

        if ($this->_require_password) {
            $rules[] = ["password", "required"];
        }

        return $rules;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'password_hash' => 'Password Hash',
            'password' => 'Password',
            'password_repeat' => 'Retype Password',
            'password_reset_token' => 'Password Reset Token',
            'access_token' => 'Access Token',
            'type' => 'Type',
            'status' => 'Active',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function behaviors()
    {
        return [
            \yii\behaviors\TimestampBehavior::className(),
        ];
    }

    public function beforeValidate()
    {
        if ($this->isNewRecord) {
            $this->generateAuthKey();
        }

        if ($this->status || $this->status == 10) {
            $this->status = self::STATUS_ACTIVE;
        } else {
            $this->status = self::STATUS_DISABLED;
        }

        if ($this->password) {
            $this->setPassword($this->password);
        }

        return parent::beforeValidate();
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        $user = self::findOne($id);

        return $user ? $user : null;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        $query = self::find()->where(["access_token" => $token]);

        if ($type) {
            $query->andWhere(["type" => $type]);
        }

        $user = $query->one();

        return $user ? $user : null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        $user = self::find()->where(["username" => $username])->one();

        return $user ? $user : null;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($accessToken)
    {
        return $this->getAccessToken() === $accessToken;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }


    /**
     * {@inheritdoc}
     */
    public function getAccessToken()
    {
        return $this->access_token;
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * Get is admin user type
     */
    public function getIsAdmin()
    {
        return $this->type == "admin";
    }

    /**
     * Get user status
     */
    public function getStatus()
    {
        switch ($this->status) {
            case self::STATUS_ACTIVE:
                return true;
            case self::STATUS_DISABLED:
                return false;
        }
    }

    /**
     * set _require_password
     * @param boolean
     */

    public function setRequiredPassword($value)
    {
        $this->_require_password = $value;
    }
}
