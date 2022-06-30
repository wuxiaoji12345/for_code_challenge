<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "swim_work_order_index".
 *
 * @property int $id
 * @property int $address_check_id 检查id
 * @property string $index_title 工单主标题
 * @property string $work_order_num 工单编号
 * @property int $type 工单类型 1普通 2紧急
 * @property int $source_type 工单来源类型 1检查整改 2用户意见反馈
 * @property string|null $info 工单详情
 * @property string|null $venue_name 工单所属场馆名称
 * @property int $venue_id 工单所属场馆id
 * @property int $commit_id 工单提交人id
 * @property int $commit_type 提交类型 1小程序提交 2后台提交
 * @property int $principal_channel_id 被委托处理人的channel id
 * @property int $status 工单状态 0未处理 1已处理待审核 3已删除
 * @property int $examine_status 审核状态 1未审核 2审核通过
 * @property int $create_time
 * @property string $update_time
 */
class WorkOrderIndex extends \common\models\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'swim_work_order_index';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['address_check_id', 'type', 'source_type', 'venue_id', 'commit_id', 'commit_type', 'principal_channel_id', 'status', 'examine_status', 'create_time'], 'integer'],
            [['info'], 'string'],
            [['update_time'], 'safe'],
            [['index_title', 'venue_name'], 'string', 'max' => 100],
            [['work_order_num'], 'string', 'max' => 32],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'address_check_id' => '检查id',
            'index_title' => '工单主标题',
            'type' => '工单类型 1普通 2紧急',
            'source_type' => '工单来源类型 1检查整改 2用户意见反馈',
            'info' => '工单详情',
            'venue_name' => '工单所属场馆名称',
            'venue_id' => '工单所属场馆id',
            'commit_id' => '工单提交人id',
            'commit_type' => '提交类型 1小程序提交 2后台提交',
            'principal_channel_id' => '被委托处理人的channel id',
            'work_order_num' => '工单编号',
            'status' => '工单状态 0未处理 1已处理待审核 3已删除',
            'examine_status' => '审核状态 1未审核 2审核通过',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }

    public function getWorkOrders(){
        return $this->hasMany(WorkOrder::className(),['index_id'=>'id']);
    }
}
