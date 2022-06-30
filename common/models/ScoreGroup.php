<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%score_group}}".
 *
 * @property int $id
 * @property int $matchid
 * @property int $ssid 赛道id
 * @property int $itemid 项目id
 * @property int $groupnum 分组编号
 * @property int $starttime 开始时间
 * @property int $endtime 结束时间
 * @property int $status 1，有效；2，删除
 * @property int $create_time
 * @property string $update_time
 */
class ScoreGroup extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%score_group}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['matchid', 'groupnum'], 'required'],
            [['matchid', 'ssid', 'itemid', 'groupnum', 'starttime', 'endtime', 'status', 'create_time'], 'integer'],
            [['update_time'], 'safe'],
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
            'starttime' => 'Starttime',
            'endtime' => 'Endtime',
            'status' => 'Status',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
