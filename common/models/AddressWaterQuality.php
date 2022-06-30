<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%address_water_quality}}".
 *
 * @property int $id
 * @property string $quality_id 泳馆水质信息id
 * @property string $address_id 场所主键id
 * @property string $address_name 场所名称
 * @property string $ph PH
 * @property string $ci CI
 * @property string $temperature 温度
 * @property string $turbidity 浊度
 * @property string $orp ORP
 * @property string $cod COD
 * @property string $conductivity 电导率
 * @property string $usea 尿素
 * @property string $device_no 设备编号
 * @property string $sampling_point 采样点
 * @property int $status 1-有效；2-删除
 * @property int $create_time
 * @property string|null $update_time
 */
class AddressWaterQuality extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%address_water_quality}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'create_time'], 'integer'],
            [['update_time'], 'safe'],
            [['quality_id', 'address_id', 'address_name'], 'string', 'max' => 32],
            [['ph', 'ci', 'temperature', 'turbidity', 'orp', 'cod', 'conductivity', 'usea', 'device_no', 'sampling_point'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'quality_id' => 'Quality ID',
            'address_id' => 'Address ID',
            'address_name' => 'Address Name',
            'ph' => 'Ph',
            'ci' => 'Ci',
            'temperature' => 'Temperature',
            'turbidity' => 'Turbidity',
            'orp' => 'Orp',
            'cod' => 'Cod',
            'conductivity' => 'Conductivity',
            'usea' => 'Usea',
            'device_no' => 'Device No',
            'sampling_point' => 'Sampling Point',
            'status' => 'Status',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
