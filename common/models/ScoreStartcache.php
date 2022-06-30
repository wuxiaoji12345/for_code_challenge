<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%score_startcache}}".
 *
 * @property int $id
 * @property int $matchid
 * @property int $ssid 赛道id
 * @property string $ssname
 * @property int $itemid 项目id
 * @property string $itemname
 * @property int $groupnum 分组编号
 * @property string $update_time
 */
class ScoreStartcache extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%score_startcache}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['matchid', 'groupnum'], 'required'],
            [['matchid', 'ssid', 'itemid', 'groupnum'], 'integer'],
            [['update_time'], 'safe'],
            [['ssname', 'itemname'], 'string', 'max' => 255],
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
            'ssname' => 'Ssname',
            'itemid' => 'Itemid',
            'itemname' => 'Itemname',
            'groupnum' => 'Groupnum',
            'update_time' => 'Update Time',
        ];
    }
}
