<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%score_states}}".
 *
 * @property int $id
 * @property int $matchid 赛事id
 * @property int $itemid 项目id
 * @property int $groupnum 分组编号
 * @property int $lane 泳道
 * @property int $enrollid 用户id
 * @property int $enrollgender 用户性别:1,男；2，女
 * @property string $enrollname 选手姓名
 * @property int $startvalue 净成绩起点
 * @property int $statevalue 状态点数值
 * @property string $statevalue_time
 * @property int $score 成绩时间
 * @property int $speed 状态点配速（每km耗时毫秒数）
 * @property int $isvalued 是否有效。0，无效；1，有效
 * @property int $groupid 分组id
 */
class ScoreStates extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%score_states}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['matchid', 'groupnum'], 'required'],
            [['matchid', 'itemid', 'groupnum', 'lane', 'enrollid', 'enrollgender', 'startvalue', 'statevalue', 'score', 'speed', 'isvalued', 'groupid'], 'integer'],
            [['statevalue_time'], 'safe'],
            [['enrollname'], 'string', 'max' => 128],
            [['itemid', 'groupnum', 'lane'], 'unique', 'targetAttribute' => ['itemid', 'groupnum', 'lane']],
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
            'itemid' => 'Itemid',
            'groupnum' => 'Groupnum',
            'lane' => 'Lane',
            'enrollid' => 'Enrollid',
            'enrollgender' => 'Enrollgender',
            'enrollname' => 'Enrollname',
            'startvalue' => 'Startvalue',
            'statevalue' => 'Statevalue',
            'statevalue_time' => 'Statevalue Time',
            'score' => 'Score',
            'speed' => 'Speed',
            'isvalued' => 'Isvalued',
            'groupid' => 'Groupid',
        ];
    }
}
