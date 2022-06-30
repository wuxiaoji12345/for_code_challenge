<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "swim_address_check".
 *
 * @property int $id
 * @property int $swim_address_id
 * @property int $user_channel_id user channel id
 * @property string $type 检查类型
 * @property string $check_num 检查编号
 * @property string $check_date
 * @property float|null $longitude 赛事经度
 * @property float|null $latitude 赛事纬度
 * @property int|null $comment_num
 * @property int $check_status 检查结果状态 1正常 2异常
 * @property int $status 1-有效；2-删除
 * @property int|null $create_time
 * @property string|null $update_time
 */
class AddressCheck extends \common\models\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'swim_address_check';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['swim_address_id', 'user_channel_id', 'check_date'], 'required'],
            [['swim_address_id', 'user_channel_id', 'comment_num', 'check_status', 'status', 'create_time'], 'integer'],
            [['check_date', 'update_time'], 'safe'],
            [['longitude', 'latitude'], 'number'],
            [['type','check_num',], 'string', 'max' => 32],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'swim_address_id' => 'Swim Address ID',
            'user_channel_id' => 'user channel id',
            'type' => '检查类型',
            'check_num' => '检查编号',
            'check_date' => 'Check Date',
            'longitude' => '赛事经度',
            'latitude' => '赛事纬度',
            'comment_num' => 'Comment Num',
            'check_status' => '检查结果状态 1正常 2异常',
            'status' => '1-有效；2-删除',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
