<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%event_type}}".
 *
 * @property int $id 参赛组id
 * @property int $matchid match 表id
 * @property string $title 自定义组别名
 * @property int $mincount 每组最小人数
 * @property int $maxcount 每组最大人数
 * @property int $fmincount 女队员最少人数
 * @property int $fmaxcount 女队员最多人数
 * @property string $agemin 最小年龄
 * @property string $agemax 最大年龄
 * @property string $fees 预付款
 * @property int $amount 最大组数量
 * @property int $num 当前剩余数量
 * @property string $notice 报名要求
 * @property int $isinvited 是否需要邀请码，1-需要；2-不需要
 * @property int $weight 权重
 * @property int $category_id 系列id
 * @property int $type 1 单人组 2 家庭 3 团队
 * @property int $rule
 * @property int $istiming '是否计时：0，无计时；1，计时',
 * @property string $groupform 团队信息模板
 * @property string $registerform 选手填写信息模板
 * @property int $registerend 修改信息截止时间，天
 * @property int $needcheck 1-不需要审核 2先审核后付款 3先付款后审核
 * @property int $registerlimit 单人报名上限；0-无上限
 * @property int $allforpay 1-先报名后加成员；2-必须有成员
 * @property int $status 1,有效;2,删除
 * @property int $create_time 创建时间
 * @property string $update_time 更新时间
 * @property string $limit_value 存放 limit的限制 逗号隔开
 * @property int $limit
 */
class __EventType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%event_type}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['matchid', 'title', 'fees', 'create_time'], 'required'],
            [['matchid', 'mincount', 'maxcount', 'fmincount', 'fmaxcount', 'amount', 'num', 'isinvited', 'weight', 'category_id', 'type', 'rule', 'istiming', 'registerend', 'needcheck', 'registerlimit', 'allforpay', 'status', 'create_time', 'limit'], 'integer'],
            [['agemin', 'agemax', 'update_time'], 'safe'],
            [['fees'], 'number'],
            [['groupform', 'registerform', 'limit_value'], 'string'],
            [['title'], 'string', 'max' => 128],
            [['notice'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '参赛组id',
            'matchid' => 'match 表id',
            'title' => '自定义组别名',
            'mincount' => '每组最小人数',
            'maxcount' => '每组最大人数',
            'fmincount' => '女队员最少人数',
            'fmaxcount' => '女队员最多人数',
            'agemin' => '最小年龄',
            'agemax' => '最大年龄',
            'fees' => '预付款',
            'amount' => '最大组数量',
            'num' => '当前剩余数量',
            'notice' => '报名要求',
            'isinvited' => '是否需要邀请码，1-需要；2-不需要',
            'weight' => '权重',
            'category_id' => '系列id',
            'type' => '1 单人组 2 家庭 3 团队',
            'rule' => 'Rule',
            'istiming' => '\'是否计时：0，无计时；1，计时\',',
            'groupform' => '团队信息模板',
            'registerform' => '选手填写信息模板',
            'registerend' => '修改信息截止时间，天',
            'needcheck' => '1-不需要审核 2先审核后付款 3先付款后审核',
            'registerlimit' => '单人报名上限；0-无上限',
            'allforpay' => '1-先报名后加成员；2-必须有成员',
            'status' => '1,有效;2,删除',
            'create_time' => '创建时间',
            'update_time' => '更新时间',
            'limit_value' => '存放 limit的限制 逗号隔开',
            'limit' => 'Limit',
        ];
    }
}
