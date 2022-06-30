<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "swim_address_facilities".
 *
 * @property int $id
 * @property int $sid 场馆id
 * @property int $locke_room 更衣室 0无 1有
 * @property int $toilet 公共卫生间 0无 1有
 * @property int $clinic 医务室 0无 1有
 * @property int $shower_room 淋浴房 0无 1有
 * @property int $circulating_equipment 池水循环设备 0无 1有
 * @property int $ventilation_facilities 通风设施 0无 1有
 * @property int $foot_soaking_tank 浸脚池 0无 1有
 * @property int $disinfection_facilities 公共用品消毒设施 0无 1有
 * @property int $status 1-有效；2-无效
 * @property string|null $update_time
 */
class AddressFacilities extends \common\models\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'swim_address_facilities';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => false,
                'updatedAtAttribute' => false,
                'value' => time()
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sid', 'locke_room', 'toilet', 'clinic', 'shower_room', 'circulating_equipment', 'ventilation_facilities', 'foot_soaking_tank', 'disinfection_facilities', 'status'], 'integer'],
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
            'sid' => '场馆id',
            'locke_room' => '更衣室 0无 1有',
            'toilet' => '公共卫生间 0无 1有',
            'clinic' => '医务室 0无 1有',
            'shower_room' => '淋浴房 0无 1有',
            'circulating_equipment' => '池水循环设备 0无 1有',
            'ventilation_facilities' => '通风设施 0无 1有',
            'foot_soaking_tank' => '浸脚池 0无 1有',
            'disinfection_facilities' => '公共用品消毒设施 0无 1有',
            'status' => '1-有效；2-无效',
            'update_time' => 'Update Time',
        ];
    }

    public static function add($params)
    {
        $model = self::findOne(['sid' => $params['sid'], 'status' => self::NORMAL_STATUS]) ?? new self();
        $model->load($params, '');
        if ($model->save()) {
            return [true, $model];
        } else {
            return [false, $model->getErrors()];
        }
    }

}
