<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "swim_address".
 *
 * @property int $id
 * @property string $address_id 游泳馆 ID
 * @property string $type 游泳馆类型 Code
 * @property string $name 场馆名称
 * @property string $avatar 游泳馆头像照片
 * @property string $license_url 许可证照片
 * @property string $imgurl 场馆图片
 * @property string $province
 * @property string $city
 * @property string $district
 * @property string $neighborhood_name 街道名称
 * @property int $neighborhood_id 街道id
 * @property string $address 泳馆地址-详细详址
 * @property string $travel_information 交通信息
 * @property string $phone 场所固定电话
 * @property int $trade_situation 营业情况（01-正常；02-休业；）
 * @property string $swim_service_type 提供服务信息
 * @property float $longitude 赛事经度
 * @property float $latitude 赛事纬度
 * @property int $lane
 * @property int $comment_num
 * @property int $comment_sum_score
 * @property int $publish 是否发布
 * @property string $account 负责人账号
 * @property int $account_id 负责人账号id
 * @property string $water_acreage 池水面积（㎡）
 * @property string $remark 场所开放时间：全年开放；夏季开放
 * @property string $open_license 开放许可证编号
 * @property string $principal 负责人姓名
 * @property string $open_object 场所开放性质：对内开放；对外开放；
 * @property int $last_access 最后更新时间
 * @property string $high_risk_deadline 高危许可证截止时间
 * @property string $legal_representative 法定代表人
 * @property string $social_credit_code 社会信用代码
 * @property int $high_risk_status 高危许可证状态 1有效 2过期
 * @property string $issuing_authority 发证机构
 * @property string $nature_business 经营范围
 * @property int $disabled 是否展示 1不展示0展示
 * @property int $collapse_flag 场所倒闭表示 1倒闭 0正常运营
 * @property string $approval_status 审批状态 0初始值 P审批中 S审批通过 C审批不通过
 * @property int $address_person 场馆社会体育指导员和救助人员数量
 * @property int $status 1-有效；2-无效
 * @property int $create_time
 * @property string|null $update_time
 */
class Address extends \common\models\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'swim_address';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['neighborhood_id', 'trade_situation', 'lane', 'comment_num', 'comment_sum_score', 'publish', 'account_id', 'last_access', 'high_risk_status', 'disabled', 'collapse_flag', 'address_person', 'status', 'create_time'], 'integer'],
            [['longitude', 'latitude'], 'number'],
            [['update_time'], 'safe'],
            [['address_id'], 'string', 'max' => 32],
            [['type', 'name', 'imgurl', 'address', 'issuing_authority', 'nature_business'], 'string', 'max' => 255],
            [['avatar', 'license_url'], 'string', 'max' => 300],
            [['province', 'city', 'district', 'neighborhood_name', 'account'], 'string', 'max' => 128],
            [['travel_information'], 'string', 'max' => 500],
            [['phone', 'open_license', 'principal', 'open_object', 'high_risk_deadline', 'legal_representative', 'social_credit_code'], 'string', 'max' => 50],
            [['swim_service_type', 'water_acreage'], 'string', 'max' => 100],
            [['remark'], 'string', 'max' => 3000],
            [['approval_status'], 'string', 'max' => 10],
        ];
    }


    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => false,
                'updatedAtAttribute' => false,
                'value' => time()
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'address_id' => '游泳馆 ID',
            'type' => '游泳馆类型 Code',
            'name' => '场馆名称',
            'avatar' => '游泳馆头像照片',
            'license_url' => '许可证照片',
            'imgurl' => '场馆图片',
            'province' => 'Province',
            'city' => 'City',
            'district' => 'District',
            'neighborhood_name' => '街道名称',
            'neighborhood_id' => '街道id',
            'address' => '泳馆地址-详细详址',
            'travel_information' => '交通信息',
            'phone' => '场所固定电话',
            'trade_situation' => '营业情况（01-正常；02-休业；）',
            'swim_service_type' => '提供服务信息',
            'longitude' => '赛事经度',
            'latitude' => '赛事纬度',
            'lane' => 'Lane',
            'comment_num' => 'Comment Num',
            'comment_sum_score' => 'Comment Sum Score',
            'publish' => '是否发布',
            'account' => '负责人账号',
            'account_id' => '负责人账号id',
            'water_acreage' => '池水面积（㎡）',
            'remark' => '场所开放时间：全年开放；夏季开放',
            'open_license' => '开放许可证编号',
            'principal' => '负责人姓名',
            'open_object' => '场所开放性质：对内开放；对外开放；',
            'last_access' => '最后更新时间',
            'high_risk_deadline' => '高危许可证截止时间',
            'legal_representative' => '法定代表人',
            'social_credit_code' => '社会信用代码',
            'high_risk_status' => '高危许可证状态 1有效 2过期',
            'issuing_authority' => '发证机构',
            'nature_business' => '经营范围',
            'disabled' => '是否展示 1不展示0展示',
            'collapse_flag' => '场所倒闭表示 1倒闭 0正常运营',
            'approval_status' => '审批状态 0初始值 P审批中 S审批通过 C审批不通过',
            'address_person' => '场馆社会体育指导员和救助人员数量',
            'status' => '1-有效；2-无效',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
