<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%address_contact_person}}".
 *
 * @property int $id
 * @property string $contact_id 联系人id
 * @property string $address_id 所属泳馆id
 * @property string $name 姓名
 * @property string $nickname 称呼
 * @property string $position 职位
 * @property int $gender 性别：0-女；1-男；
 * @property string $landline_phone 联系电话
 * @property string $phone 手机
 * @property string $email 邮箱
 * @property int $is_default 是否默认联系人：0-是；1-否；
 * @property int|null $last_access 最后更新时间
 * @property int $status 1-有效；2-删除
 * @property int|null $create_time
 * @property string|null $update_time
 */
class AddressContactPerson extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%address_contact_person}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['gender', 'is_default', 'last_access', 'status', 'create_time'], 'integer'],
            [['update_time'], 'safe'],
            [['contact_id', 'address_id'], 'string', 'max' => 32],
            [['position', 'name', 'email'], 'string', 'max' => 100],
            [['nickname', 'landline_phone'], 'string', 'max' => 20],
            [['phone'], 'verificationMobile'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'contact_id' => 'Contact ID',
            'address_id' => 'Address ID',
            'name' => 'Name',
            'nickname' => 'Nickname',
            'position' => 'Position',
            'gender' => 'Gender',
            'landline_phone' => 'Landline Phone',
            'phone' => 'Phone',
            'email' => 'Email',
            'is_default' => 'Is Default',
            'last_access' => 'Last Access',
            'status' => 'Status',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }

    public function verificationMobile($attribute, $params)
    {
        $preg_phone = '/^(13[0-9]|14[01456879]|15[0-35-9]|16[2567]|17[0-8]|18[0-9]|19[0-35-9])\d{8}$/';
        if (!preg_match($preg_phone, $this->phone)) {
            $this->addError($attribute, '手机号有误，请检查！');
        }
    }
}
