<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "swim_event_members".
 *
 * @property int $id 参赛人id
 * @property int $gid 企业id
 * @property int $urid 注册用户id
 * @property string $name 姓名
 * @property string $mobile 手机号
 * @property string $sex 1-男；2-女
 * @property string $idtype 证件类型；1-身份证；2-护照；3-通行证；4-其他
 * @property string $idnumber 证件号
 * @property string $birth 生日
 * @property string $avatar 近照url
 * @property string $nation 民族
 * @property string $memberinfos 用户信息
 * @property string $size 服装尺码
 * @property int $status 1，有效；2，删除
 * @property int $create_time 创建时间
 * @property string $update_time 更新时间
 */
class __EventMembers extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'swim_event_members';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['gid', 'urid', 'status', 'create_time'], 'integer'],
            [['name'], 'required'],
            [['birth', 'update_time'], 'safe'],
            [['memberinfos'], 'string'],
            [['name', 'nation'], 'string', 'max' => 64],
            [['mobile'], 'string', 'max' => 13],
            [['sex', 'idtype', 'idnumber'], 'string', 'max' => 255],
            [['avatar'], 'string', 'max' => 128],
            [['size'], 'string', 'max' => 16],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'gid' => 'Gid',
            'urid' => 'Urid',
            'name' => 'Name',
            'mobile' => 'Mobile',
            'sex' => 'Sex',
            'idtype' => 'Idtype',
            'idnumber' => 'Idnumber',
            'birth' => 'Birth',
            'avatar' => 'Avatar',
            'nation' => 'Nation',
            'memberinfos' => 'Memberinfos',
            'size' => 'Size',
            'status' => 'Status',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
