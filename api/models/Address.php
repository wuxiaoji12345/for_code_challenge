<?php

namespace api\models;

use common\models\BaseModel;
use Yii;

/**
 * This is the model class for table "{{%address}}".
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
 * @property int $swimmers_total_number 泳客总人数
 * @property int $swimmers_healthy_number 泳客健康承诺人数
 * @property int $swimmers_Insurance_number 泳客保险人数
 * @property int $daily_passenger_flow 每日客流
 * @property int $status 1-有效；2-无效
 * @property int $create_time
 * @property string|null $update_time
 */
class Address extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%address}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['address_id', 'lane'], 'required'],
            [['neighborhood_id', 'trade_situation', 'lane', 'comment_num', 'comment_sum_score', 'publish', 'account_id', 'last_access', 'swimmers_total_number', 'swimmers_healthy_number', 'swimmers_Insurance_number', 'daily_passenger_flow', 'status', 'create_time'], 'integer'],
            [['longitude', 'latitude'], 'number'],
            [['update_time'], 'safe'],
            [['address_id'], 'string', 'max' => 32],
            [['type', 'name', 'imgurl', 'address'], 'string', 'max' => 255],
            [['avatar', 'license_url'], 'string', 'max' => 300],
            [['province', 'city', 'district', 'neighborhood_name', 'account'], 'string', 'max' => 128],
            [['travel_information'], 'string', 'max' => 500],
            [['phone', 'open_license', 'principal', 'open_object'], 'string', 'max' => 50],
            [['swim_service_type', 'water_acreage'], 'string', 'max' => 100],
            [['remark'], 'string', 'max' => 3000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'address_id' => 'Address ID',
            'type' => 'Type',
            'name' => 'Name',
            'avatar' => 'Avatar',
            'license_url' => 'License Url',
            'imgurl' => 'Imgurl',
            'province' => 'Province',
            'city' => 'City',
            'district' => 'District',
            'neighborhood_name' => 'Neighborhood Name',
            'neighborhood_id' => 'Neighborhood ID',
            'address' => 'Address',
            'travel_information' => 'Travel Information',
            'phone' => 'Phone',
            'trade_situation' => 'Trade Situation',
            'swim_service_type' => 'Swim Service Type',
            'longitude' => 'Longitude',
            'latitude' => 'Latitude',
            'lane' => 'Lane',
            'comment_num' => 'Comment Num',
            'comment_sum_score' => 'Comment Sum Score',
            'publish' => 'Publish',
            'account' => 'Account',
            'account_id' => 'Account ID',
            'water_acreage' => 'Water Acreage',
            'remark' => 'Remark',
            'open_license' => 'Open License',
            'principal' => 'Principal',
            'open_object' => 'Open Object',
            'last_access' => 'Last Access',
            'swimmers_total_number' => 'Swimmers Total Number',
            'swimmers_healthy_number' => 'Swimmers Healthy Number',
            'swimmers_Insurance_number' => 'Swimmers  Insurance Number',
            'daily_passenger_flow' => 'Daily Passenger Flow',
            'status' => 'Status',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }

    const STATUS_VALID = 1;
    const STATUS_INVALID = 2;

    public function apiSearch($name = '',$district = '')
    {
        $data = $this->find()
            ->asArray()
            ->select(['id', 'name', 'imgurl',  'avatar',  'address', 'comment_num', 'comment_sum_score', 'neighborhood_name', 'longitude', 'latitude'])
            ->where([
                'status' => self::STATUS_VALID
            ])
            ->andFilterWhere([
                'like', 'name', $name
            ])
            ->andFilterWhere([
                '=', 'district', $district
            ])
            ->andFilterWhere(['publish'=>1])
            ->all();

        return $data;
    }

    public function getNameByID($id)
    {
        $data = $this->find()
            ->select('name')
            ->where(['id' => $id])
            ->one();
        if (isset($data->name)) {
            return $data->name;
        }

        return '-';
    }
}
