<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "swim_work_order_history".
 *
 * @property int $id
 * @property int $work_order_id 细分类工单id
 * @property int $operation_id 工单操作人
 * @property string $operation_name 工单操作人姓名
 * @property int $operation_type 工单操作人类型 1检查人员 2场馆负责人
 * @property int $operation_status 操作类型 1已提交照片 2审核通过 3审核不通过
 * @property string $handle_img 处理图片
 * @property string|null $handle_notes 处理备注
 * @property string|null $feedback_notes 反馈备注
 * @property int $create_time
 * @property string $update_time
 */
class WorkOrderHistory extends \common\models\BaseModel
{
    const INSPECTOR = 1;
    const LEADER = 2;
    const OPERATION_TYPE_CN = [self::INSPECTOR=>'检查人员',self::LEADER=>'场馆负责人'];

    const SUBMITTED = 1;
    const APPROVED = 2;
    const AUDIT_FAILED = 3;
    const OPERATION_STATUS_CN = [self::SUBMITTED=>'已提交照片', self::APPROVED=>'审核通过', self::AUDIT_FAILED=>'审核不通过'];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'swim_work_order_history';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['work_order_id', 'operation_id', 'operation_type', 'operation_status', 'create_time'], 'integer'],
            [['handle_notes', 'feedback_notes'], 'string'],
            [['update_time'], 'safe'],
            [['operation_name'], 'string', 'max' => 100],
            [['handle_img'], 'string', 'max' => 1024],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'work_order_id' => 'Work Order ID',
            'operation_id' => 'Operation ID',
            'operation_name' => 'Operation Name',
            'operation_type' => 'Operation Type',
            'operation_status' => 'Operation Status',
            'handle_img' => 'Handle Img',
            'handle_notes' => 'Handle Notes',
            'feedback_notes' => 'Feedback Notes',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
