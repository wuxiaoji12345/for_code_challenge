<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%user_channel}}".
 *
 * @property int $id
 * @property int $urid 用户id
 * @property string $unionid
 * @property string $openid
 * @property string $session_key
 * @property string $token
 * @property int $gid 企业id
 * @property int $app 来源
 * @property string $extappid 第三方推广应用appid
 * @property int $dist 渠道号
 * @property int $status 1-有效；2-无效
 * @property string $update_time
 * @property int $create_time
 */
class UserChannel extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user_channel}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['urid', 'unionid', 'openid'], 'required'],
            [['urid', 'gid', 'app', 'dist', 'status', 'create_time'], 'integer'],
            [['update_time'], 'safe'],
            [['unionid', 'extappid'], 'string', 'max' => 64],
            [['openid', 'session_key', 'token'], 'string', 'max' => 255],
            [['openid'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'urid' => 'Urid',
            'unionid' => 'Unionid',
            'openid' => 'Openid',
            'session_key' => 'Session Key',
            'token' => 'Token',
            'gid' => 'Gid',
            'app' => 'App',
            'extappid' => 'Extappid',
            'dist' => 'Dist',
            'status' => 'Status',
            'update_time' => 'Update Time',
            'create_time' => 'Create Time',
        ];
    }
}
