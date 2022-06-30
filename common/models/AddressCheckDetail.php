<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "swim_address_check_detail".
 *
 * @property integer $id
 * @property integer $swim_address_check_id
 * @property string $swim_address_check_item_id
 * @property string $result
 * @property string $item_snapshot
 * @property integer $status
 * @property integer $check_status
 * @property integer $create_time
 * @property string $update_time
 */
class AddressCheckDetail extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%address_check_detail}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['swim_address_check_id', 'swim_address_check_item_id', 'result'], 'required'],
            [['swim_address_check_id', 'status', 'check_status', 'create_time'], 'integer'],
            [['update_time'], 'safe'],
            [['swim_address_check_item_id'], 'string', 'max' => 64],
            [['item_snapshot'], 'string', 'max' => 256],
            [['result'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'swim_address_check_id' => 'Swim Address Check ID',
            'swim_address_check_item_id' => 'Swim Address Check Item ID',
            'result' => '检查结果',
            'item_snapshot' => '检查内容快照',
            'status' => '1-有效；2-删除',
            'check_status' => '1-正常；2-异常',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
