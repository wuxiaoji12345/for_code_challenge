<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%match_session}}".
 *
 * @property int $id
 * @property int $matchid 赛事id
 * @property string $name 场次名
 * @property string $start_time 赛事开始时间
 * @property string $province
 * @property string $city
 * @property string $district
 * @property string $stadium 场馆名称
 * @property string $address 某场比赛地点
 * @property double $longitude 赛事经度
 * @property double $latitude 赛事纬度
 * @property int $lane
 * @property int $status 1-有效；2-无效
 * @property int $swim_address_id 场地id
 * @property int $register_count 单场报名项目数量
 * @property int $create_time
 * @property string $update_time
 * @property string $cert_template 证书模板
 */
class MatchSession extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%match_session}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['matchid', 'swim_address_id', 'start_time'], 'required'],
            [['matchid', 'lane', 'status', 'swim_address_id', 'register_count', 'create_time'], 'integer'],
            [['start_time', 'update_time'], 'safe'],
            [['longitude', 'latitude'], 'number'],
            [['name', 'stadium', 'address','cert_template'], 'string', 'max' => 255],
            [['province', 'city', 'district'], 'string', 'max' => 128],
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
            'name' => 'Name',
            'start_time' => 'Start Time',
            'province' => 'Province',
            'city' => 'City',
            'district' => 'District',
            'stadium' => 'Stadium',
            'address' => 'Address',
            'longitude' => 'Longitude',
            'latitude' => 'Latitude',
            'lane' => 'Lane',
            'status' => 'Status',
            'swim_address_id' => 'Swim Address ID',
            'register_count' => 'Register Count',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'cert_template' => '证书模板',
        ];
    }
}
