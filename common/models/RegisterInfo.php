<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%register_info}}".
 *
 * @property int $id 参赛人id
 * @property int $rrid 关联register_relation中ID
 * @property int $matchid register_activity活动ID
 * @property int $typeid 对应register_type
 * @property int $memberid 成员id
 * @property string $certificate 证书
 * @property string $checkinnumber 检录识别号码
 * @property int $number 号码布号码
 * @property string $checkinavatar 检录时照片
 * @property int $ischeckin 是否已检录1-是；2-否
 * @property int $state 1-有效；2-无效
 * @property string $usercode 选手码
 * @property string $name
 * @property string $mobile
 * @property string $sex
 * @property string $idtype
 * @property string $idnumber
 * @property string $birth
 * @property string $avatar
 * @property string $nation
 * @property string $registerinfos
 * @property string $size
 * @property string $dense_fea
 * @property int $gnum
 * @property string $subgnum
 * @property int $orderindex 棒次
 * @property int $create_time 创建时间
 * @property string $update_time 更新时间
 */
class RegisterInfo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%register_info}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rrid', 'matchid', 'typeid', 'memberid', 'number', 'ischeckin', 'state', 'gnum', 'orderindex', 'create_time'], 'integer'],
            [['matchid', 'typeid'], 'required'],
            [['registerinfos', 'dense_fea'], 'string'],
            [['update_time'], 'safe'],
            [['certificate', 'checkinavatar', 'name', 'mobile', 'sex', 'idtype', 'idnumber', 'birth', 'avatar', 'nation', 'size'], 'string', 'max' => 255],
            [['checkinnumber'], 'string', 'max' => 128],
            [['usercode'], 'string', 'max' => 32],
            [['subgnum'], 'string', 'max' => 4],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'rrid' => 'Rrid',
            'matchid' => 'Matchid',
            'typeid' => 'Typeid',
            'memberid' => 'Memberid',
            'certificate' => 'Certificate',
            'checkinnumber' => 'Checkinnumber',
            'number' => 'Number',
            'checkinavatar' => 'Checkinavatar',
            'ischeckin' => 'Ischeckin',
            'state' => 'State',
            'usercode' => 'Usercode',
            'name' => 'Name',
            'mobile' => 'Mobile',
            'sex' => 'Sex',
            'idtype' => 'Idtype',
            'idnumber' => 'Idnumber',
            'birth' => 'Birth',
            'avatar' => 'Avatar',
            'nation' => 'Nation',
            'registerinfos' => 'Registerinfos',
            'size' => 'Size',
            'dense_fea' => 'Dense Fea',
            'gnum' => 'Gnum',
            'subgnum' => 'Subgnum',
            'orderindex' => 'Orderindex',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
