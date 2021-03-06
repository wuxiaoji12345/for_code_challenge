<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property integer $gid
 * @property integer $role
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 * @property string $phone
 * @property string $wsaf_urid
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 2;
    const STATUS_ACTIVE = 1;

    public $password;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%bk_user}}';
    }



    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username','nickname','password'], 'required','on'=>'create'],
            [['username','nickname'], 'required','on'=>'update'],
            [['gid','status', 'create_time'], 'integer'],
            [['update_time','wsaf_urid'], 'safe'],
            ['username','unique'],
            [['create_time'], 'default','value'=>time()],
            [['username', 'auth_key','phone'], 'string', 'max' => 32],
            [['password_hash', 'password_reset_token', 'nickname','role'], 'string', 'max' => 256],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['create_time', 'default', 'value' => time()],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            [['unionid'], 'string', 'max' => 100],
            [['wsaf_urid'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户名',
            'password'=>'密码',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'nickname' => '昵称',
            'status' => 'Status',
            'create_time' => '添加时间',
            'update_time' => 'Update Time',
            'gid'=>'所属企业',
            'pid'=>'上级主管',
            'role'=>'角色',
            'phone'=>'联系电话',
            'wsaf_urid'=>'wsaf_urid',
        ];
    }




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
        $result =    self::find()->where(['auth_key'=>$token])->one();
        return $result;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
//         return static::findOne(['user' => $username]);
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

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
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
}
