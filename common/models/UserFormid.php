<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%user_formid}}".
 *
 * @property int $id
 * @property int $urid 用户id
 * @property string $openid
 * @property string $formid  表单id
 * @property int $app 来源
 * @property int $status 1-有效；2-无效
 * @property string $update_time
 * @property int $create_time
 */
class UserFormid extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user_formid}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['urid', 'openid'], 'required'],
            [['urid', 'app', 'status', 'create_time'], 'integer'],
            [['update_time'], 'safe'],
            [['openid', 'formid'], 'string', 'max' => 255],
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
            'openid' => 'Openid',
            'formid' => 'Formid',
            'app' => 'App',
            'status' => 'Status',
            'update_time' => 'Update Time',
            'create_time' => 'Create Time',
        ];
    }
}
