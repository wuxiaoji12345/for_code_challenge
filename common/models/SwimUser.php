<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property int $id
 * @property string $unionid
 * @property string $phone 绑定手机
 * @property int $role 0,未认证; 1,学生; 2,不是学生
 * @property int $status 1-有效；2-无效
 * @property string $update_time
 * @property int $create_time
 */
class SwimUser extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['role', 'status', 'create_time'], 'integer'],
            [['update_time'], 'safe'],
            [['unionid', 'phone'], 'string', 'max' => 64],
            [['unionid', 'phone'], 'unique', 'targetAttribute' => ['unionid', 'phone']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'unionid' => 'Unionid',
            'phone' => 'Phone',
            'role' => 'Role',
            'status' => 'Status',
            'update_time' => 'Update Time',
            'create_time' => 'Create Time',
        ];
    }
}
