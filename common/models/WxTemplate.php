<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%wx_template}}".
 *
 * @property int $id
 * @property string $wx_template 小程序推送模版id
 * @property string $name 模版名称
 * @property string $sms_template 短信模版
 * @property string $sms_sign 短信签名
 * @property int $app
 * @property int $type 类型：1,赛事注册成功 2审核通过提醒
 * @property string $update_time
 */
class WxTemplate extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%wx_template}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['app', 'type'], 'integer'],
            [['update_time'], 'safe'],
            [['wx_template'], 'string', 'max' => 255],
            [['name'], 'string', 'max' => 32],
            [['sms_template'], 'string', 'max' => 64],
            [['sms_sign'], 'string', 'max' => 128],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'wx_template' => 'Wx Template',
            'name' => 'Name',
            'sms_template' => 'Sms Template',
            'sms_sign' => 'Sms Sign',
            'app' => 'App',
            'type' => 'Type',
            'update_time' => 'Update Time',
        ];
    }
}
