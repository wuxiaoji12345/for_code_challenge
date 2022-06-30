<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "swim_address_lifeguard_certificate".
 *
 * @property int $id
 * @property int $lifeguard_id 救生员id
 * @property int $three_personnel_id 三类人员id
 * @property string $practice_certificate_code 执业证书编号
 * @property string $certificate_effective_date 证书生效日期
 * @property string $recent_training_date 最近培训日期
 * @property string $practice_certificate_url 执业证书图片地址
 * @property int $cert_type 证件类型 1-救生员证；2-国职证书
 * @property string $cert_level 证书级别 初级 中级 高级
 * @property int $status 1-有效；2-删除
 * @property int|null $create_time
 * @property string|null $update_time
 */
class AddressLifeguardCertificate extends \common\models\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'swim_address_lifeguard_certificate';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lifeguard_id', 'three_personnel_id', 'cert_type', 'status', 'create_time'], 'integer'],
            [['update_time'], 'safe'],
            [['practice_certificate_code', 'certificate_effective_date', 'recent_training_date'], 'string', 'max' => 100],
            [['practice_certificate_url'], 'string', 'max' => 1000],
            [['cert_level'], 'string', 'max' => 16],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'lifeguard_id' => '救生员id',
            'three_personnel_id' => '三类人员id',
            'practice_certificate_code' => '执业证书编号',
            'certificate_effective_date' => '证书生效日期',
            'recent_training_date' => '最近培训日期',
            'practice_certificate_url' => '执业证书图片地址',
            'cert_type' => '证件类型 1-救生员证；2-国职证书',
            'cert_level' => '证书级别 初级 中级 高级',
            'status' => '1-有效；2-删除',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
