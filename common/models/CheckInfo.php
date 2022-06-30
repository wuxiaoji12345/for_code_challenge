<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "swim_check_info".
 *
 * @property int $id
 * @property string $name 检察员姓名
 * @property string $mobile 绑定手机
 * @property int $user_channel_id 检察员绑定的user channel id
 * @property int $id_card 身份证号码
 * @property string $grant_date 发证日期
 * @property string $certificates_code 检查证编号
 * @property string $effective_date 有效期时间
 * @property int $certificates_status 证状态 1有效 2无效
 * @property int $age 年龄
 * @property string $img_url 图片
 * @property int $gender 性别 1男 2女
 * @property int $area_code 区域code
 * @property int $status 1-有效；2-无效
 * @property int $create_time
 */
class CheckInfo extends \common\models\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'swim_check_info';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_channel_id', 'certificates_status', 'age', 'gender', 'area_code', 'status', 'create_time'], 'integer'],
            [['name', 'mobile', 'grant_date', 'certificates_code', 'effective_date'], 'string', 'max' => 64],
            [['img_url'], 'string', 'max' => 1000],
            [['name', 'mobile'], 'unique', 'targetAttribute' => ['name', 'mobile']],
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
            'name' => '检察员姓名',
            'mobile' => '绑定手机',
            'user_channel_id' => '检察员绑定的user channel id',
            'id_card' => '身份证号码',
            'grant_date' => '发证日期',
            'certificates_code' => '检查证编号',
            'effective_date' => '有效期时间',
            'certificates_status' => '证状态 1有效 2无效',
            'age' => '年龄',
            'img_url' => '图片',
            'gender' => '性别 1男 2女',
            'area_code' => '区域code',
            'status' => '1-有效；2-无效',
            'create_time' => 'Create Time',
        ];
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
