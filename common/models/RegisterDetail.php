<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%register_detail}}".
 *
 * @property int $id id
 * @property int $rrid 订单id
 * @property int $ssid 场次id
 * @property string $start_time 赛事开始时间
 * @property string $province
 * @property string $city
 * @property string $district
 * @property string $stadium 场馆名称
 * @property double $longitude
 * @property double $latitude
 * @property int $itemid1 项目1
 * @property string $itemname1
 * @property int $itemid2 项目2
 * @property string $itemname2
 * @property int $check_state1 1，已检录；2，未检录
 * @property int $check_state2 1，已检录；2，未检录
 * @property int $create_time 创建时间
 * @property string $update_time 更新时间
 */
class RegisterDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%register_detail}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rrid'], 'required'],
            [['rrid', 'ssid', 'itemid1', 'itemid2', 'check_state1', 'check_state2', 'create_time'], 'integer'],
            [['start_time', 'update_time'], 'safe'],
            [['longitude', 'latitude'], 'number'],
            [['province', 'city', 'district', 'itemname1', 'itemname2'], 'string', 'max' => 128],
            [['stadium'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'rrid' => 'Rrid',
            'ssid' => 'Ssid',
            'start_time' => 'Start Time',
            'province' => 'Province',
            'city' => 'City',
            'district' => 'District',
            'stadium' => 'Stadium',
            'longitude' => 'Longitude',
            'latitude' => 'Latitude',
            'itemid1' => 'Itemid1',
            'itemname1' => 'Itemname1',
            'itemid2' => 'Itemid2',
            'itemname2' => 'Itemname2',
            'check_state1' => 'Check State1',
            'check_state2' => 'Check State2',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
