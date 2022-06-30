<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "swim_pool".
 *
 * @property int $id
 * @property int $sid 场馆id
 * @property string $name 池子名称
 * @property string $type 泳池类型
 * @property string $temperature 温度类型
 * @property string $long 泳池长
 * @property string $wide 泳池宽
 * @property string $max_water_depth 最大水深
 * @property string $area 面积
 * @property int $quantity 数量
 * @property int $weight 排序权重
 * @property int $status 1-有效；2-无效
 * @property string|null $update_time
 */
class Pool extends \common\models\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'swim_pool';
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
            [['sid', 'quantity', 'weight', 'status'], 'integer'],
            [['update_time'], 'safe'],
            [['name'], 'string', 'max' => 100],
            [['type', 'temperature'], 'string', 'max' => 20],
            [['long', 'wide', 'max_water_depth', 'area'], 'string', 'max' => 10],
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
            'name' => '池子名称',
            'type' => '泳池类型',
            'temperature' => '温度类型',
            'long' => '泳池长',
            'wide' => '泳池宽',
            'max_water_depth' => '最大水深',
            'area' => '面积',
            'quantity' => '数量',
            'weight' => '排序权重',
            'status' => '1-有效；2-无效',
            'update_time' => 'Update Time',
        ];
    }
}
