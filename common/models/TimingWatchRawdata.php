<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%timing_watch_rawdata}}".
 *
 * @property int $id
 * @property int $matchid 赛事id
 * @property int $ssid 场次id
 * @property int $itemid 项目id
 * @property int $groupnum 组别号码
 * @property int $lane 泳道
 * @property int $start_time 开始计时时间
 * @property int $end_time 结束计时时间
 * @property int $time 耗时
 * @property int $status 1，有效；0，删除
 * @property int $create_time
 * @property string $update_time
 * @property string $uuid 设备ID
 */
class TimingWatchRawdata extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%timing_watch_rawdata}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['matchid', 'ssid', 'itemid', 'groupnum', 'lane', 'start_time', 'end_time', 'time', 'status', 'create_time'], 'integer'],
            [['ssid', 'lane'], 'required'],
            [['update_time'], 'safe'],
            [['uuid'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'matchid' => '赛事id',
            'ssid' => '场次id',
            'itemid' => '项目id',
            'groupnum' => '组别号码',
            'lane' => '泳道',
            'start_time' => '开始计时时间',
            'end_time' => '结束计时时间',
            'time' => '耗时',
            'status' => '1，有效；0，删除',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'uuid' => '设备ID',
        ];
    }
}
