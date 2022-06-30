<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%score_enroll}}".
 *
 * @property int $id
 * @property int $matchid
 * @property int $ssid 场次id
 * @property int $itemid 项目id
 * @property string $number 号码簿
 * @property string $chipid 芯片号
 * @property string $name 姓名
 * @property string $unit 单位或团队名称
 * @property string $phone 手机号
 * @property string $idcard 身份证号
 * @property int $gender 性别:1,男；2，女
 * @property int $order 接力赛顺序
 * @property int $point ptsa积分
 * @property int $additionaltime 罚时或减时
 * @property string $additionalreason 理由
 * @property int $ischeckin 1-已检录；2-未检录
 * @property string $extrainfo 额外信息：json格式，k-v对
 * @property int $type 1, 线上用户；2，线下用户
 * @property int $status 1，有效；2，删除；
 * @property int $create_time
 * @property string $update_time
 */
class ScoreEnroll extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%score_enroll}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['matchid'], 'required'],
            [['matchid', 'ssid', 'itemid', 'gender', 'order', 'point', 'additionaltime', 'ischeckin', 'type', 'status', 'create_time'], 'integer'],
            [['extrainfo'], 'string'],
            [['update_time'], 'safe'],
            [['number', 'chipid'], 'string', 'max' => 64],
            [['name', 'idcard'], 'string', 'max' => 128],
            [['unit'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 32],
            [['additionalreason'], 'string', 'max' => 1024],
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
            'ssid' => 'Ssid',
            'itemid' => 'Itemid',
            'number' => 'Number',
            'chipid' => 'Chipid',
            'name' => 'Name',
            'unit' => 'Unit',
            'phone' => 'Phone',
            'idcard' => 'Idcard',
            'gender' => 'Gender',
            'order' => 'Order',
            'point' => 'Point',
            'additionaltime' => 'Additionaltime',
            'additionalreason' => 'Additionalreason',
            'ischeckin' => 'Ischeckin',
            'extrainfo' => 'Extrainfo',
            'type' => 'Type',
            'status' => 'Status',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
