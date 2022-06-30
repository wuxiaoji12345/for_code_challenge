<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "swim_address_three_personnel".
 *
 * @property int $id
 * @property int $address_id 场馆id
 * @property string $address_name 场馆名称
 * @property string $personnel_id 从业人员id
 * @property string $id_card 身份证号
 * @property string $card_no 制卡卡号
 * @property string $date_of_issuance 发证日期-起始
 * @property string $date_of_issuance_end 发证日期-截止
 * @property string $id_card_image 身份证照片
 * @property string $name 姓名
 * @property string $nation 民族
 * @property int $gender 性别：0-女；1-男；
 * @property string $education 学历
 * @property string $account_address 户籍所在地地址
 * @property string $phone 手机
 * @property int $type 人员类型：01-池主任；02-救 生组长；03-水质管理员；04- 检查人员；
 * @property string $level 级别
 * @property int $age 年龄
 * @property string $card_status 证件状态
 * @property int $work_year 工作年限
 * @property string $service_area 服务区域，如：黄埔区，杨浦 区（多个区域逗号分隔）
 * @property int $last_access 最后更新时间
 * @property int $status 1-有效；2-删除
 * @property int $create_time
 * @property string|null $update_time
 */
class AddressThreePersonnel extends \common\models\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'swim_address_three_personnel';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['address_id', 'gender', 'type', 'age', 'work_year', 'last_access', 'status', 'create_time'], 'integer'],
            [['update_time'], 'safe'],
            [['address_name'], 'string', 'max' => 50],
            [['personnel_id'], 'string', 'max' => 32],
            [['card_no', 'date_of_issuance', 'date_of_issuance_end', 'name'], 'string', 'max' => 100],
            [['id_card_image'], 'string', 'max' => 1000],
            [['nation', 'level', 'card_status', 'service_area'], 'string', 'max' => 10],
            [['education'], 'string', 'max' => 20],
            [['account_address'], 'string', 'max' => 500],
            [['phone', 'id_card'], 'required'],
            [['phone'], 'verificationMobile'],
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
            'address_id' => '场馆id',
            'address_name' => '场馆名称',
            'personnel_id' => '从业人员id',
            'id_card' => '身份证号',
            'card_no' => '制卡卡号',
            'date_of_issuance' => '发证日期-起始',
            'date_of_issuance_end' => '发证日期-截止',
            'id_card_image' => '身份证照片',
            'name' => '姓名',
            'nation' => '民族',
            'gender' => '性别：0-女；1-男；',
            'education' => '学历',
            'account_address' => '户籍所在地地址',
            'phone' => '手机',
            'type' => '人员类型：01-池主任；02-救 生组长；03-水质管理员；04- 检查人员；',
            'level' => '级别',
            'age' => '年龄',
            'card_status' => '证件状态',
            'work_year' => '工作年限',
            'service_area' => '服务区域，如：黄埔区，杨浦 区（多个区域逗号分隔）',
            'last_access' => '最后更新时间',
            'status' => '1-有效；2-删除',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }

    public function getAddressLifeguardCertificates()
    {
        return $this->hasMany(AddressLifeguardCertificate::className(), ['three_personnel_id' => 'id']);
    }

    public function verificationMobile($attribute, $params)
    {
        $preg_phone = '/^(13[0-9]|14[01456879]|15[0-35-9]|16[2567]|17[0-8]|18[0-9]|19[0-35-9])\d{8}$/';
        if (!preg_match($preg_phone, $this->phone)) {
            $this->addError($attribute, '手机号有误，请检查！');
        }
    }

    public function verificationIdCard($attribute, $params)
    {
        $preg_card = '/^[1-9]\d{5}(18|19|20)\d{2}((0[1-9])|(1[0-2]))(([0-2][1-9])|10|20|30|31)\d{3}[0-9Xx]$/';
        if (!preg_match($preg_card, $this->id_card)) {
            $this->addError($attribute, '身份证号有误，请检查！');
        }
    }
}
