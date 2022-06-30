<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%register_relation}}".
 *
 * @property int $id 组织id
 * @property string $order_no 每步订单号
 * @property string $trade_no 支付流水号
 * @property int $urid 报名人id
 * @property int $matchid register_activity id
 * @property int $typeid 对应register_type
 * @property string $typename 分组名称快照
 * @property double $orgfees 原价
 * @property string $fees 费用
 * @property int $state 1已支付；2未支付，3-已退款
 * @property int $paytype 支付方式: 1.支付宝支付；2.微信支付
 * @property int $sendnotice 是否已发送 0否 1是
 * @property int $lastpaytime 最近支付时间
 * @property string $paytime 支付成功时间
 * @property string $payinfo 支付回调信息
 * @property string $name 注册人姓名
 * @property string $mobile 收短信手机
 * @property int $type 1 单人组 2 家庭 3 团队
 * @property int $ischeck 审核状态，1-通过；2-未通过
 * @property string $regname 团队名称
 * @property string $unit 单位名称
 * @property string $leader 领队
 * @property string $leadermobile 领队电话
 * @property string $groupcode 队伍码
 * @property int $gnum 队伍编号
 * @property string $groupinfos
 * @property int $app app来源
 * @property int $create_time 创建时间
 * @property string $update_time 更新时间
 */
class RegisterRelation extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%register_relation}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['urid', 'matchid', 'typeid', 'state', 'paytype', 'sendnotice', 'lastpaytime', 'type', 'ischeck', 'gnum', 'app', 'create_time'], 'integer'],
            [['orgfees', 'fees'], 'number'],
            [['paytime', 'update_time'], 'safe'],
            [['payinfo', 'groupinfos'], 'string'],
            [['create_time'], 'required'],
            [['order_no', 'leader', 'leadermobile'], 'string', 'max' => 32],
            [['trade_no', 'typename', 'name'], 'string', 'max' => 64],
            [['mobile', 'regname'], 'string', 'max' => 255],
            [['unit'], 'string', 'max' => 128],
            [['groupcode'], 'string', 'max' => 16],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_no' => 'Order No',
            'trade_no' => 'Trade No',
            'urid' => 'Urid',
            'matchid' => 'Matchid',
            'typeid' => 'Typeid',
            'typename' => 'Typename',
            'orgfees' => 'Orgfees',
            'fees' => 'Fees',
            'state' => 'State',
            'paytype' => 'Paytype',
            'sendnotice' => 'Sendnotice',
            'lastpaytime' => 'Lastpaytime',
            'paytime' => 'Paytime',
            'payinfo' => 'Payinfo',
            'name' => 'Name',
            'mobile' => 'Mobile',
            'type' => 'Type',
            'ischeck' => 'Ischeck',
            'regname' => 'Regname',
            'unit' => 'Unit',
            'leader' => 'Leader',
            'leadermobile' => 'Leadermobile',
            'groupcode' => 'Groupcode',
            'gnum' => 'Gnum',
            'groupinfos' => 'Groupinfos',
            'app' => 'App',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
