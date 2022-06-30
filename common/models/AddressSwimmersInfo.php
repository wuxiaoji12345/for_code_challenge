<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "swim_address_swimmers_info".
 *
 * @property int $id
 * @property string $stat_date 统计日期
 * @property int $swimmers_total_number 泳客总人数
 * @property int $swimmers_healthy_number 泳客健康承诺人数
 * @property int $swimmers_Insurance_number 泳客保险人数
 * @property int $daily_passenger_flow 每日客流
 * @property int $create_time
 * @property string|null $update_time
 */
class AddressSwimmersInfo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'swim_address_swimmers_info';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['swimmers_total_number', 'swimmers_healthy_number', 'swimmers_Insurance_number', 'daily_customer_flow', 'create_time'], 'integer'],
            [['update_time'], 'safe'],
            [['stat_date'], 'string', 'max' => 50],
            [['stat_date'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'stat_date' => '统计日期',
            'swimmers_total_number' => '泳客总人数',
            'swimmers_healthy_number' => '泳客健康承诺人数',
            'swimmers_Insurance_number' => '泳客保险人数',
            'daily_customer_flow' => '每日客流',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
