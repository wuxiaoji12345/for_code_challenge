<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "swim_address_fitness_card_signin".
 *
 * @property int $id
 * @property string $signin_id 天健id
 * @property string $swim_pool_id 关联天健场馆id
 * @property string $address_name 游泳馆商户名  
 * @property string $district 场馆所属区
 * @property string $date 日期
 * @property int $last_access 最后更新时间
 * @property int $create_time
 * @property string|null $update_time
 */
class AddressFitnessCardSignin extends \common\models\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'swim_address_fitness_card_signin';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['last_access', 'create_time'], 'integer'],
            [['update_time','date'], 'safe'],
            [['signin_id', 'swim_pool_id'], 'string', 'max' => 32],
            [['address_name'], 'string', 'max' => 200],
            [['district'], 'string', 'max' => 50],
        ];
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
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'signin_id' => '天健id',
            'swim_pool_id' => '关联天健场馆id',
            'address_name' => '游泳馆商户名',
            'district' => '场馆所属区',
            'last_access' => '最后更新时间',
            'date' => '日期',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
