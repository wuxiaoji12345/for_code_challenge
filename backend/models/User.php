<?php

namespace backend\models;

use backend\models\MatchFuncEnterpise;
use common\models\model\Enterprise;
use common\models\model\MatchFunc;
use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\web\HttpException;
use yii\web\IdentityInterface;
use yii\helpers\ArrayHelper;
use yii\web\ServerErrorHttpException;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_time
 * @property integer $updated_time
 * @property string $password write-only password
 * @property string $unionid 微信登录 unionid
 * @property string $mpinfo 微信其他信息
 * @property string $role 角色
 */
class User extends ActiveRecord implements IdentityInterface
{
    CONST STATUS_DELETED = 2;
    CONST STATUS_ACTIVE = 1;

    //用户权限相关
    CONST USER = 0;
    CONST ADMIN = 1;
    CONST PROJECTADMIN = 2;
    CONST MATERIAL = 3;
    CONST USER_MY_MATCH = 4;
    //前后端角色对应关系表
    CONST ROLE_MAPS = [
        self::USER => '普通用户',
        self::ADMIN => '系统管理员',
//        self::PROJECTADMIN=>'项目审批',
//        self::MATERIAL=>'物资管理',
        self::USER_MY_MATCH => '私有赛事管理',
    ];


    //权限结束
    public function fields()
    {
        $fields = parent::fields();
        // 删除一些包含敏感信息的字段
        unset($fields['auth_key'], $fields['password_hash'], $fields['password_reset_token']);
//        $fields['app']  =
        return $fields;
    }

    public function extraFields()
    {
        return ['enterpriseInfo', 'fun', 'enterprise'];
    }


    public $password;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%bk_user}}';
    }


    public function beforeSave($insert)
    {

        if (parent::beforeSave($insert)) {
            if ($insert) {
                //只有管理员才可以操作
                $userrole = json_decode(Yii::$app->user->getIdentity()->role, true);
                if (!in_array(self::ADMIN, $userrole)) throw new HttpException(500, '非法操作');
                $this->gid = Yii::$app->user->getIdentity()->gid;
                $this->setPassword($this->password);
                $this->generateAuthKey();
                $this->generatePasswordResetToken();
            } else {
                if (Yii::$app->user->getIdentity()->gid != $this->gid) {
                    $userrole = json_decode(Yii::$app->user->getIdentity()->role, true);
                    if (!in_array(self::PROJECTADMIN, $userrole)) throw new HttpException(500, '非法操作');
                }
                if ($this->password) {
                    $this->setPassword($this->password);
                    $this->generateAuthKey();
                    $this->generatePasswordResetToken();
                }
            }
        }
        return true;

    }


    public function afterSave($insert, $changedAttributes)
    {

        parent::afterSave($insert, $changedAttributes);

        if ($insert || isset($changedAttributes['role'])) {
            if($insert && (!$this->role || isset($changedAttributes['role'])))
            {
                return;
            }
            try {
                $auth = Yii::$app->authManager;
                $auth->revokeAll($this->id);
                $rolePermissions = [];
                //企业权限
                $gid = Yii::$app->user->getIdentity()->gid;
                //新增 或者 修改role
                if ((!$insert && !empty($changedAttributes['role'])) || $insert) {
                    $role = json_decode($this->role, true);
                    $rolePermissions = [];
                    foreach ($role as $key => $v) {
                        $rolePermissions = array_merge($rolePermissions, $auth->getPermissionsByRole(self::ROLE_MAPS[$v * 1]));
                    }
                    $rolePermissions = array_unique((array_keys($rolePermissions)));
                }

                $matchFuncs = MatchFuncEnterpise::find()
                    ->andWhere(['gid' => $gid])
                    ->joinWith(['router' => function ($query) use ($rolePermissions) {
                        return $query->andWhere([
                            'bkrule' => $rolePermissions
                        ]);

                    }])
                    ->asArray()
                    ->all();


                if (is_array($matchFuncs)) {
                    foreach ($matchFuncs as $key => $v) {
                        $authRole = $auth->getPermission($v['router']['bkrule']);
                        $isPermission = Yii::$app->authManager->checkAccess($this->id, $authRole->name);
                        if ($isPermission) continue;
                        $auth->assign($authRole, $this->id);
                    }
                }
                return true;

            } catch (\Exception $e) {


                throw new ServerErrorHttpException($e->getMessage());
            }

        } else {
            return true;
        }
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'nickname', 'password', 'area_code'], 'required', 'on' => 'create'],
            [['username', 'nickname'], 'required', 'on' => 'update'],
            [['status', 'create_time', 'area_code', 'channel_id'], 'integer'],
            [['update_time', 'avatar', 'password', 'auth_key', 'wsaf_urid', 'nickname', 'phone', 'role'], 'safe'],
            ['username', 'unique'],
            [['mpinfo'], 'string'],
            [['create_time'], 'default', 'value' => time()],
            [['username'], 'string', 'max' => 32],
            [['password_hash', 'password_reset_token', 'nickname'], 'string', 'max' => 256],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['create_time', 'default', 'value' => time()],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            [['unionid'], 'string', 'max' => 100],
            [['gid', 'username'], 'unique', 'targetAttribute' => ['gid', 'username']]
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
            'password' => '密码',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'nickname' => '昵称',
            'area_code' => '区域',
            'status' => 'Status',
            'create_time' => '添加时间',
            'update_time' => 'Update Time',
            'gid' => '所属企业',
            'pid' => '上级主管',
            'phone' => '手机号',
            'wsaf_urid' => 'wsaf_urid',
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
        $result = self::find()
            ->where(['auth_key' => $token])->one();

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

    public function getEnterpriseInfo()
    {
        return $this->hasOne(EnterpriseInfo::className(), ['gid' => 'gid']);
    }

    public function getEnterprise()
    {
        return $this->hasOne(Enterprise::className(), ['id' => 'gid']);
    }

    public function getFun()
    {
        return $this->hasMany(MatchFuncEnterpise::className(), ['gid' => 'gid'])->onCondition([MatchFuncEnterpise::tableName() . '.status' => 1])
            ->orderBy([MatchFuncEnterpise::tableName() . '.weight' => SORT_DESC, MatchFuncEnterpise::tableName() . '.id' => SORT_ASC]);
    }

    /**
     * 获取角色用户
     * @param $role
     * @return array
     */
    public static function getRoleList($role)
    {

        $userList = User::find()
            ->andWhere(['gid' => Yii::$app->user->identity->gid, 'status' => self::STATUS_ACTIVE])
            ->asArray()
            ->all();
        $roleUserList = [];
        foreach ($userList as $key => $user) {
            try {
                $userrole = json_decode($user['role'], true);
                if (in_array($role, $userrole)) {
                    array_push($roleUserList, $user);
                }
            } catch (\Exception $e) {
            }
        }
        return $roleUserList;
    }

    /**
     * 获取单个
     * @param $role
     * @return mixed|void|ActiveRecord
     */
    public static function getRole($role)
    {
        $userList = User::find()
            ->andWhere(['gid' => Yii::$app->user->identity->gid, 'status' => self::STATUS_ACTIVE])
            ->all();
        foreach ($userList as $user) {
            try {
                $userrole = json_decode($user->role, true);
                if (in_array($role, $userrole)) {
                    return $user;
                }
            } catch (\Exception $e) {
            }
        }
        return;
    }

    /**
     * @return string
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function isAdmin()
    {

       $roles = Yii::$app->user->identity->role;
       if($roles){
           $roles   =   json_decode($roles,true);
           return in_array(self::ADMIN,$roles);

       }
       return false;

    }





}
