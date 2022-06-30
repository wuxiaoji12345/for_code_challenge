<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%user_info}}".
 *
 * @property int $id
 * @property int $urid
 * @property int $totalpoint 总积分
 * @property string $nickname
 * @property string $avatarurl
 * @property string $country
 * @property int $gender
 * @property string $province
 * @property string $city
 * @property string $language
 * @property int $create_time
 * @property string $update_time
 * @property int $status 1-有效；2-无效
 */
class UserInfo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user_info}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['urid', 'totalpoint', 'gender', 'create_time', 'status'], 'integer'],
            [['update_time'], 'safe'],
            [['nickname'], 'string', 'max' => 64],
            [['avatarurl', 'country', 'province', 'city', 'language'], 'string', 'max' => 255],
            [['urid'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'urid' => 'Urid',
            'totalpoint' => 'Totalpoint',
            'nickname' => 'Nickname',
            'avatarurl' => 'Avatarurl',
            'country' => 'Country',
            'gender' => 'Gender',
            'province' => 'Province',
            'city' => 'City',
            'language' => 'Language',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'status' => 'Status',
        ];
    }
}
