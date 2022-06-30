<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%member_score_record}}".
 *
 * @property int $id id
 * @property int $memberid
 * @property int $type 1 任务领取 2 兑换消耗 3好友赞助 4 赞助好友
 * @property int $value
 * @property int $matchid 赛事id
 * @property int $ssid 场次id
 * @property int $itemid 项目id
 * @property string $description 描述
 * @property int $status 1-有效；2-无效
 * @property int $create_time 创建时间
 * @property string $update_time 更新时间
 */
class MemberScoreRecord extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%member_score_record}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['memberid', 'type', 'value', 'matchid', 'ssid', 'itemid', 'status', 'create_time'], 'integer'],
            [['update_time'], 'safe'],
            [['description'], 'string', 'max' => 64],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'memberid' => 'Memberid',
            'type' => 'Type',
            'value' => 'Value',
            'matchid' => 'Matchid',
            'ssid' => 'Ssid',
            'itemid' => 'Itemid',
            'description' => 'Description',
            'status' => 'Status',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
