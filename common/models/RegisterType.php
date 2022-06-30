<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%register_type}}".
 *
 * @property int $id 参赛组id
 * @property int $matchid match 表id
 * @property string $title 自定义组别名
 * @property int $mincount 每组最小人数
 * @property int $maxcount 每组最大人数
 * @property int $fmincount 女队员最少人数
 * @property int $fmaxcount 女队员最多人数
 * @property string $fees 预付款
 * @property int $amount 最大组数量
 * @property int $num 当前剩余数量
 * @property string $notice 报名要求
 * @property int $type 1 单场 2联票
 * @property string $groupform 团队信息模板
 * @property string $registerform 选手填写信息模板
 * @property int $needcheck 1-不需要审核;2需要
 * @property int $registerlimit 单人报名上限；0-无上限
 * @property int $allforpay 1-先报名后加成员；2-必须有成员
 * @property int $weight 权重
 * @property int $create_time 创建时间
 * @property string $update_time 更新时间
 */
class RegisterType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%register_type}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['matchid', 'title', 'create_time'], 'required'],
            [['matchid', 'mincount', 'maxcount', 'fmincount', 'fmaxcount', 'amount', 'num', 'type', 'needcheck', 'registerlimit', 'allforpay', 'weight', 'create_time'], 'integer'],
            [['fees'], 'number'],
            [['groupform', 'registerform'], 'string'],
            [['update_time'], 'safe'],
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
            'id' => 'ID',
            'matchid' => 'Matchid',
            'title' => 'Title',
            'mincount' => 'Mincount',
            'maxcount' => 'Maxcount',
            'fmincount' => 'Fmincount',
            'fmaxcount' => 'Fmaxcount',
            'fees' => 'Fees',
            'amount' => 'Amount',
            'num' => 'Num',
            'notice' => 'Notice',
            'type' => 'Type',
            'groupform' => 'Groupform',
            'registerform' => 'Registerform',
            'needcheck' => 'Needcheck',
            'registerlimit' => 'Registerlimit',
            'allforpay' => 'Allforpay',
            'weight' => 'Weight',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
