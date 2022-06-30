<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "swim_operation_log".
 *
 * @property int $id
 * @property int $operation_id 操作人id
 * @property string $operation_name 操作人名称
 * @property string $operation_time 操作时间
 * @property string $operation_model 操作的模块
 * @property string $operation_event 操作事件
 * @property string $ip ip地址
 * @property int $status 1-有效；2-删除
 * @property int $create_time
 * @property string|null $update_time
 */
class OperationLog extends \common\models\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'swim_operation_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['operation_id'], 'required'],
            [['operation_id', 'status', 'create_time'], 'integer'],
            [['operation_time', 'update_time'], 'safe'],
            [['operation_name'], 'string', 'max' => 64],
            [['operation_model', 'operation_event'], 'string', 'max' => 50],
            [['ip'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'operation_id' => '操作人id',
            'operation_name' => '操作人名称',
            'operation_time' => '操作时间',
            'operation_model' => '操作的模块',
            'operation_event' => '操作事件',
            'ip' => 'ip地址',
            'status' => '1-有效；2-删除',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
