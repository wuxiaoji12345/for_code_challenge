<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "swim_address_lifeguard".
 *
 * @property int $id
 * @property int $swim_address_id
 * @property string $lifeguard_id 救生员天健id
 * @property string $tianjian_pool_id 天健的场馆id
 * @property string $name 姓名
 * @property string $avatar 头像 Url 地址
 * @property string $birth 出生年月
 * @property string $email 邮箱
 * @property string $introduction 个人简介
 * @property string $coach_id 救生员/教练id
 * @property int $type 人员类型（如：01-救生员、02-教练）'
 * @property int $gender 性别 1-男，2-女
 * @property string $mobile 手机
 * @property string $id_card 身份证
 * @property int $last_access 最后更新时间
 * @property int $status 1-有效；2-删除
 * @property int|null $create_time
 * @property string|null $update_time
 */
class AddressLifeguard extends \common\models\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'swim_address_lifeguard';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['swim_address_id', 'type', 'gender', 'last_access', 'status', 'create_time'], 'integer'],
            [['update_time'], 'safe'],
            [['lifeguard_id', 'tianjian_pool_id', 'name', 'avatar'], 'string', 'max' => 255],
            [['birth'], 'string', 'max' => 20],
            [['email'], 'string', 'max' => 100],
            [['introduction'], 'string', 'max' => 6000],
            [['coach_id', 'id_card'], 'string', 'max' => 32],
//            [['mobile'], 'string', 'max' => 16],
            [['mobile','id_card'], 'required'],
            [['mobile'], 'verificationMobile'],
            [['id_card'], 'verificationIdCard'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'swim_address_id' => 'Swim Address ID',
            'tianjian_pool_id' => '天健的场馆id',
            'lifeguard_id' => '救生员天健id',
            'name' => '姓名',
            'avatar' => '头像 Url 地址',
            'birth' => '出生年月',
            'email' => '邮箱',
            'introduction' => '个人简介',
            'coach_id' => '救生员/教练id',
            'type' => '人员类型（如：01-救生员、02-教练）\'',
            'gender' => '性别 1-男，2-女',
            'mobile' => '手机',
            'id_card' => '身份证',
            'last_access' => '最后更新时间',
            'status' => '1-有效；2-删除',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }

    public function getAddressLifeguardCertificates()
    {
        return $this->hasMany(AddressLifeguardCertificate::className(), ['lifeguard_id' => 'id']);
    }

    public function verificationMobile($attribute, $params)
    {
        $preg_phone = '/^(13[0-9]|14[01456879]|15[0-35-9]|16[2567]|17[0-8]|18[0-9]|19[0-35-9])\d{8}$/';
        if (!preg_match($preg_phone, $this->mobile)) {
            $this->addError($attribute, '手机号有误，请检查！');
        }
    }

    public function verificationIdCard($attribute, $params)
    {
        $preg_card='/^[1-9]\d{5}(18|19|20)\d{2}((0[1-9])|(1[0-2]))(([0-2][1-9])|10|20|30|31)\d{3}[0-9Xx]$/';
        if (!preg_match($preg_card, $this->id_card)) {
            $this->addError($attribute, '身份证号有误，请检查！');
        }
    }
}
