<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%wx_token}}".
 *
 * @property int $id
 * @property string $wx_token
 * @property int $urid 用户的token，需要urid
 * @property string $openid 用户urid对应的openid
 * @property int $expires_in
 * @property int $app
 * @property int $type 类型：1，短信，小程序访问token
 * @property int $create_time
 * @property string $update_time
 */
class WxToken extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%wx_token}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['urid', 'expires_in', 'app', 'type', 'create_time'], 'integer'],
            [['update_time'], 'safe'],
            [['wx_token'], 'string', 'max' => 255],
            [['openid'], 'string', 'max' => 64],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'wx_token' => 'Wx Token',
            'urid' => 'Urid',
            'openid' => 'Openid',
            'expires_in' => 'Expires In',
            'app' => 'App',
            'type' => 'Type',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
