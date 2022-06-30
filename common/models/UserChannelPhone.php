<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%user_channel_phone}}".
 *
 * @property int $id
 * @property int $urid 用户id
 * @property string $phone
 * @property string $password
 * @property string $code 验证码
 * @property int $code_expired
 * @property string $send_date 验证码发送日期
 * @property int $send_num 当天发送次数
 * @property string $token
 * @property int $gid 企业id
 * @property int $app 来源
 * @property string $extappid 第三方推广应用appid
 * @property int $dist 渠道号
 * @property int $status 1-有效；2-无效
 * @property string $update_time
 * @property int $create_time
 */
class UserChannelPhone extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user_channel_phone}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['urid', 'phone'], 'required'],
            [['urid', 'code_expired', 'send_num', 'gid', 'app', 'dist', 'status', 'create_time'], 'integer'],
            [['send_date', 'update_time'], 'safe'],
            [['phone', 'password', 'extappid'], 'string', 'max' => 64],
            [['code'], 'string', 'max' => 16],
            [['token'], 'string', 'max' => 255],
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
            'phone' => 'Phone',
            'password' => 'Password',
            'code' => 'Code',
            'code_expired' => 'Code Expired',
            'send_date' => 'Send Date',
            'send_num' => 'Send Num',
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
