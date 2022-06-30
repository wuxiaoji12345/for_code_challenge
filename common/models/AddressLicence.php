<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "swim_address_licence".
 *
 * @property int $id
 * @property int $address_id 关联场馆id
 * @property string $imgurl 证照图片
 * @property string $remarks 备注
 * @property int $type 类型 1-其他证照 2-场馆其他照片
 * @property int $status 1-有效；2-无效
 * @property int $create_time
 * @property string|null $update_time
 */
class AddressLicence extends \common\models\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'swim_address_licence';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['address_id', 'type', 'status', 'create_time'], 'integer'],
            [['update_time'], 'safe'],
            [['imgurl'], 'string', 'max' => 255],
            [['remarks'], 'string', 'max' => 128],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'address_id' => '关联场馆id',
            'imgurl' => '证照图片',
            'remarks' => '备注',
            'type' => '类型 1-其他证照 2-场馆其他照片',
            'status' => '1-有效；2-无效',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
