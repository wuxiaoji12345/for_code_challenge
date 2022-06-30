<?php

namespace common\models\base;

use common\models\BaseModel;
use Yii;

/**
 * This is the model class for table "swim_user_channel_extra".
 *
 * @property int $id
 * @property int $user_channel_id user channel id
 * @property string $realname 姓名
 * @property int $is_checker 是否为场馆检查员 1-是；2-否
 * @property int $is_super_checker 是否为场馆检查员 1-是；2-否
 * @property int $is_owner 场馆id
 * @property int $status 1-有效；2-无效
 * @property string $update_time
 * @property int $create_time
 */
class __UserChannelExtra extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'swim_user_channel_extra';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_channel_id'], 'required'],
            [['user_channel_id', 'is_checker', 'is_super_checker', 'is_owner', 'status', 'create_time'], 'integer'],
            [['update_time'], 'safe'],
            [['realname'], 'string', 'max' => 128],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_channel_id' => 'User Channel ID',
            'realname' => 'Realname',
            'is_checker' => 'Is Checker',
            'is_owner' => 'Is Owner',
            'status' => 'Status',
            'is_super_checker' => 'Is super checker',
            'update_time' => 'Update Time',
            'create_time' => 'Create Time',
        ];
    }
}
