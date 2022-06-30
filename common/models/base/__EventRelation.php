<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%event_relation}}".
 *
 * @property int $id 组织id
 * @property int $rgid 管理register_group中的id
 * @property string $fees 费用
 * @property string $trade_no 支付流水号
 * @property string $order_no 每步订单号
 * @property string $refund_no 退款单号
 * @property string $out_refund_no 商户退款单号
 * @property string $refund_desc 退款原因
 * @property int $state 0-无效订单; 1已支付；2未支付，3-已退款, 101-支付超时 102-退款中
 * @property int $paytype 支付方式: 1.支付宝支付；2.微信支付 3.系统后台操作
 * @property int $typeid 对应register_type
 * @property int $matchid register_activity id
 * @property string $mobile 收短信手机
 * @property int $sendnotice 是否已发送 0否 1是
 * @property int $lastpaytime 最近支付时间
 * @property string $paytime 支付成功时间
 * @property string $name 注册人姓名
 * @property string $typename 分组名称
 * @property int $gnum 队伍编号
 * @property string $speccode 特别码
 * @property int $seosource 支付渠道，10000-每步
 * @property string $specfees 特别折扣费
 * @property int $urid 报名人id
 * @property int $category_id
 * @property string $payinfo 支付回调信息
 * @property string $reqinfo 退款回调信息
 * @property double $orgfees 原价
 * @property double $specdiscount 折扣率
 * @property int $type 1 单人组 2 家庭 3 团队
 * @property int $ischeck 审核状态，1-通过；2-未通过
 * @property int $gid 企业id
 * @property int $app app来源
 * @property int $create_time 创建时间
 * @property string $update_time 更新时间
 * @property string $invitecode 邀请码 
 */
class __EventRelation extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%event_relation}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rgid', 'create_time'], 'required'],
            [['rgid', 'state', 'paytype', 'typeid', 'matchid', 'sendnotice', 'lastpaytime', 'gnum', 'seosource', 'urid', 'category_id', 'type', 'ischeck', 'gid', 'app', 'create_time'], 'integer'],
            [['fees', 'specfees', 'orgfees', 'specdiscount'], 'number'],
            [['paytime', 'update_time'], 'safe'],
            [['payinfo', 'reqinfo'], 'string'],
            [['trade_no', 'out_refund_no', 'name', 'typename'], 'string', 'max' => 64],
            [['order_no', 'refund_no'], 'string', 'max' => 32],
            [['refund_desc'], 'string', 'max' => 20],
            [['mobile', 'invitecode'], 'string', 'max' => 255],
            [['speccode'], 'string', 'max' => 8],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '组织id',
            'rgid' => '管理register_group中的id',
            'fees' => '费用',
            'trade_no' => '支付流水号',
            'order_no' => '每步订单号',
            'refund_no' => '退款单号',
            'out_refund_no' => '商户退款单号',
            'refund_desc' => '退款原因',
            'state' => '0-无效订单; 1已支付；2未支付，3-已退款, 101-支付超时 102-退款中',
            'paytype' => '支付方式: 1.支付宝支付；2.微信支付 3.系统后台操作',
            'typeid' => '对应register_type',
            'matchid' => 'register_activity id',
            'mobile' => '收短信手机',
            'sendnotice' => '是否已发送 0否 1是',
            'lastpaytime' => '最近支付时间',
            'paytime' => '支付成功时间',
            'name' => '注册人姓名',
            'typename' => '分组名称',
            'gnum' => '队伍编号',
            'speccode' => '特别码',
            'seosource' => '支付渠道，10000-每步',
            'specfees' => '特别折扣费',
            'urid' => '报名人id',
            'category_id' => 'Category ID',
            'payinfo' => '支付回调信息',
            'reqinfo' => '退款回调信息',
            'orgfees' => '原价',
            'specdiscount' => '折扣率',
            'type' => '1 单人组 2 家庭 3 团队',
            'ischeck' => '审核状态，1-通过；2-未通过',
            'gid' => '企业id',
            'app' => 'app来源',
            'create_time' => '创建时间',
            'update_time' => '更新时间',
            'invitecode' => '邀请码 ',
        ];
    }
}
