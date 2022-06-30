<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "swim_match_session_item".
 *
 * @property int $id
 * @property int|null $matchid 赛事id
 * @property int|null $ssid 场次id
 * @property int|null $typeid
 * @property string|null $name 项目名称
 * @property int|null $type 项目代码：1，自由泳；2，蝶泳；3，仰泳；4，蛙泳；5，混合
 * @property int|null $gender 1，仅男子；2，仅女子；3，男女都有
 * @property int|null $distance 游泳距离
 * @property int|null $agemin 最小年龄
 * @property int|null $agemax 最大年龄
 * @property int|null $status 1-有效；2-无效
 * @property int|null $weight 权重排序，数字越大，越靠前
 * @property int|null $create_time
 * @property string|null $update_time
 */
class __MatchSessionItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'swim_match_session_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['matchid', 'ssid', 'typeid', 'type', 'gender', 'distance', 'agemin', 'agemax', 'status', 'weight', 'create_time'], 'integer'],
            [['update_time'], 'safe'],
            [['name'], 'string', 'max' => 128],
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
            'typeid' => 'Typeid',
            'name' => '项目名称',
            'type' => '项目代码：1，自由泳；2，蝶泳；3，仰泳；4，蛙泳；5，混合',
            'gender' => '1，仅男子；2，仅女子；3，男女都有',
            'distance' => '游泳距离',
            'agemin' => '最小年龄',
            'agemax' => '最大年龄',
            'status' => '1-有效；2-无效',
            'weight' => '权重排序，数字越大，越靠前',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
