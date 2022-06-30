<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "{{%event_info}}".
 *
 * @property int $id 参赛人id
 * @property string $certificate 证书
 * @property int $rgid 组关联，register_group
 * @property int $matchid register_activity活动ID
 * @property int $typeid 对应register_type
 * @property string $checkinnumber 检录识别号码
 * @property int $number 号码布号码
 * @property int $memberid 成员id
 * @property int $rrid 关联register_relation中ID
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
class __EventInfo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%event_info}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rgid', 'matchid', 'typeid'], 'required'],
            [['rgid', 'matchid', 'typeid', 'number', 'memberid', 'rrid', 'ischeckin', 'state', 'gnum', 'orderindex', 'create_time'], 'integer'],
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
            'id' => '参赛人id',
            'certificate' => '证书',
            'rgid' => '组关联，register_group',
            'matchid' => 'register_activity活动ID',
            'typeid' => '对应register_type',
            'checkinnumber' => '检录识别号码',
            'number' => '号码布号码',
            'memberid' => '成员id',
            'rrid' => '关联register_relation中ID',
            'checkinavatar' => '检录时照片',
            'ischeckin' => '是否已检录1-是；2-否',
            'state' => '1-有效；2-无效',
            'usercode' => '选手码',
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
            'orderindex' => '棒次',
            'create_time' => '创建时间',
            'update_time' => '更新时间',
        ];
    }
}
