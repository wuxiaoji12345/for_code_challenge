<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "swim_work_order".
 *
 * @property int $id
 * @property int $index_id 工单所属主工单id
 * @property string $title 工单标题
 * @property string $img_url 图片地址
 * @property string|null $info 工单详情
 * @property int $type 工单类型 1普通 2紧急
 * @property string $venue_name 工单所属场馆名称
 * @property int $venue_id 工单所属场馆id
 * @property int $commit_id 工单提交人id
 * @property int $commit_type 提交类型 1小程序提交 2后台提交
 * @property string|null $handle_notes 处理备注
 * @property string $handle_img 处理图片
 * @property string|null $feedback_notes 反馈备注
 * @property int $status 工单状态 0待受理 1已受理 2已关闭
 * @property int $feedback_status 反馈状态 0未反馈 1同意2不同意
 * @property int $create_time
 * @property string $update_time
 */
class WorkOrder extends \common\models\BaseModel
{
    const PENDING = 0;
    const ACCEPTED = 1;
    const CLOSED = 2;


    const NOT_APPROVED = 0;
    const AGREE = 1;
    const DISAGREE = 2;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'swim_work_order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['index_id', 'type', 'venue_id', 'commit_id', 'commit_type', 'status', 'feedback_status', 'create_time'], 'integer'],
            [['info', 'handle_notes', 'feedback_notes'], 'string'],
            [['update_time'], 'safe'],
            [['title', 'venue_name'], 'string', 'max' => 100],
            [['img_url', 'handle_img'], 'string', 'max' => 1024],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'index_id' => 'Index ID',
            'title' => 'Title',
            'img_url' => 'Img Url',
            'info' => 'Info',
            'type' => 'Type',
            'venue_name' => 'Venue Name',
            'venue_id' => 'Venue ID',
            'commit_id' => 'Commit ID',
            'commit_type' => 'Commit Type',
            'handle_notes' => 'Handle Notes',
            'handle_img' => 'Handle Img',
            'feedback_notes' => 'Feedback Notes',
            'status' => 'Status',
            'feedback_status' => 'Feedback Status',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
