<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "swim_address_check_item".
 *
 * @property integer $id
 * @property string $name
 * @property integer $level
 * @property integer $pid
 * @property integer $weight
 * @property integer $status
 * @property string $info
 * @property string $national_standard
 * @property integer $create_time
 * @property string $update_time
 */
class AddressCheckItem extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%address_check_item}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'level'], 'required'],
            [['level', 'pid', 'weight', 'status', 'create_time'], 'integer'],
            [['update_time'], 'safe'],
            [['name'], 'string', 'max' => 64],
            [['info'], 'string', 'max' => 256],
            [['national_standard'], 'string', 'max' => 1000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '检查项',
            'level' => '项目等级',
            'pid' => '父项目',
            'weight' => '权重',
            'status' => '状态',
            'info' => '检查内容',
            'national_standard' => '国标依据',
            'create_time' => '创建时间',
            'update_time' => '更新时间',
        ];
    }
}
