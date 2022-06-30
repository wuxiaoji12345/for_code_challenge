<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "swim_address_neighborhood".
 *
 * @property int $id
 * @property string|null $name 街道名称
 * @property string|null $update_time
 */
class AddressNeighborhood extends \common\models\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'swim_address_neighborhood';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['update_time'], 'safe'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'update_time' => 'Update Time',
        ];
    }
}
