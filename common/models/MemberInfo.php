<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%member_info}}".
 *
 * @property int $id 参赛人id
 * @property string $name 姓名
 * @property string $gender 1-男；2-女
 * @property string $idtype 证件类型；1-身份证；2-护照；3-通行证；4-其他
 * @property string $idnumber 证件号
 * @property string $birth 生日
 * @property string $avatar 近照url
 * @property string $nation 民族
 * @property int $score 得分
 * @property int $create_time 创建时间
 * @property string $update_time 更新时间
 */
class MemberInfo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%member_info}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['birth', 'update_time'], 'safe'],
            [['score', 'create_time'], 'integer'],
            [['name', 'nation'], 'string', 'max' => 64],
            [['gender'], 'string', 'max' => 2],
            [['idtype'], 'string', 'max' => 16],
            [['idnumber'], 'string', 'max' => 32],
            [['avatar'], 'string', 'max' => 128],
            [['idtype', 'idnumber'], 'unique', 'targetAttribute' => ['idtype', 'idnumber']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'gender' => 'Gender',
            'idtype' => 'Idtype',
            'idnumber' => 'Idnumber',
            'birth' => 'Birth',
            'avatar' => 'Avatar',
            'nation' => 'Nation',
            'score' => 'Score',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
