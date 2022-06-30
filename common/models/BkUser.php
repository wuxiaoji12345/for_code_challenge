<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%bk_user}}".
 *
 * @property int $id
 * @property int|null $gid 企业id
 * @property string $username
 * @property string $channel_id
 * @property string $area_code
 * @property string $auth_key
 * @property string $password_hash
 * @property string|null $password_reset_token
 * @property string $nickname
 * @property int|null $pid 上级id
 * @property int $status
 * @property int $create_time
 * @property string $update_time
 * @property int|null $allowance
 * @property int|null $allowance_updated_at
 * @property string|null $avatar
 * @property string|null $role 1,赞助商
 * @property string|null $phone 联系电话
 * @property string|null $email Email
 * @property string|null $unionid
 * @property string|null $mpinfo 微信其他信息
 * @property string|null $wsaf_urid wsaf对应的用户ID
 * @property int|null $hp_urid 黄浦平台用户id
 * @property int $asid 协会id
 * @property int|null $password_lock_time 用户密码锁定时间
 * @property int|null $password_lock_inventory 用户密码可以使用次数
 * @property string|null $last_login_time 最后一次登录时间
 * @property string|null $realname 真实姓名
 * @property int|null $created_at
 * @property string|null $updated_at
 */
class BkUser extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%bk_user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['gid', 'pid', 'status', 'create_time', 'allowance', 'allowance_updated_at', 'hp_urid', 'asid', 'password_lock_time', 'password_lock_inventory', 'created_at', 'channel_id', 'area_code'], 'integer'],
            [['username', 'auth_key', 'password_hash', 'nickname', 'create_time'], 'required'],
            [['update_time', 'last_login_time', 'updated_at'], 'safe'],
            [['mpinfo'], 'string'],
            [['username', 'phone'], 'string', 'max' => 32],
            [['auth_key', 'avatar', 'role', 'realname'], 'string', 'max' => 255],
            [['password_hash', 'password_reset_token', 'nickname'], 'string', 'max' => 256],
            [['email', 'unionid'], 'string', 'max' => 100],
            [['wsaf_urid'], 'string', 'max' => 64],
            [['username'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'gid' => 'Gid',
            'username' => 'Username',
            'channel_id' => 'channel_id',
            'area_code' => 'area_code',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'nickname' => 'Nickname',
            'pid' => 'Pid',
            'status' => 'Status',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'allowance' => 'Allowance',
            'allowance_updated_at' => 'Allowance Updated At',
            'avatar' => 'Avatar',
            'role' => 'Role',
            'phone' => 'Phone',
            'email' => 'Email',
            'unionid' => 'Unionid',
            'mpinfo' => 'Mpinfo',
            'wsaf_urid' => 'Wsaf Urid',
            'hp_urid' => 'Hp Urid',
            'asid' => 'Asid',
            'password_lock_time' => 'Password Lock Time',
            'password_lock_inventory' => 'Password Lock Inventory',
            'last_login_time' => 'Last Login Time',
            'realname' => 'Realname',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    CONST STATUS_DELETED = 2;
    CONST STATUS_ACTIVE = 1;
    CONST APP  = 1;
    public function fields()
    {
        $fields = parent::fields();
        // 删除一些包含敏感信息的字段
        unset($fields['auth_key'], $fields['password_hash'], $fields['password_reset_token']);
        return $fields;
    }

    public $password;

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        $result = self::find()
            ->where(['auth_key' => $token])->one();

        return $result;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return \common\models\BkUser|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
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

    public function generateAccessToken()
    {
        return Yii::$app->security->generateRandomString();
    }

    public function existUserName($username)
    {
        $cnt = $this->find()
            ->where([
                'username' => $username
            ])
            ->count();
        if ($cnt > 0) {
            return true;
        }

        return false;
    }
}
