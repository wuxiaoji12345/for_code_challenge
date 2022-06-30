<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%timing_rawdata}}".
 *
 * @property int $id
 * @property int $matchid 赛事id
 * @property int $ssid 场次id
 * @property int $itemid 项目id
 * @property int $groupnum 组别号码
 * @property int $lane 泳道：0，表示开始时间
 * @property int $type 1, 发枪时间;  2,结束时间
 * @property int $time 时间毫秒数
 * @property int $status 1，有效；0，删除
 * @property int $create_time
 * @property string $update_time
 */
class TimingRawdata extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%timing_rawdata}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['matchid', 'ssid', 'itemid', 'groupnum', 'lane', 'type', 'time', 'status', 'create_time'], 'integer'],
            [['create_time'], 'required'],
            [['update_time'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'matchid' => 'Matchid',
            'ssid' => 'Ssid',
            'itemid' => 'Itemid',
            'groupnum' => 'Groupnum',
            'lane' => 'Lane',
            'type' => 'Type',
            'time' => 'Time',
            'status' => 'Status',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
