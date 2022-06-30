<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%image_face_category}}".
 *
 * @property int $id
 * @property int $matchid
 * @property int $urid 用户id
 * @property string $username 用户名
 * @property int $face_num 基准图片人脸个数
 * @property string $imgurl 基准图片url
 * @property int $gender 性别: 1，男性；2，女性
 * @property int $age 年龄
 * @property string $dense_fea 特征值数组
 * @property int $mobile
 */
class ImageFaceCategory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%image_face_category}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['matchid', 'urid', 'face_num', 'gender', 'age', 'mobile'], 'integer'],
            [['age'], 'required'],
            [['dense_fea'], 'string'],
            [['username'], 'string', 'max' => 128],
            [['imgurl'], 'string', 'max' => 1024],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'matchid' => 'Matchid',
            'urid' => 'Urid',
            'username' => 'Username',
            'face_num' => 'Face Num',
            'imgurl' => 'Imgurl',
            'gender' => 'Gender',
            'age' => 'Age',
            'dense_fea' => 'Dense Fea',
            'mobile' => 'Mobile',
        ];
    }
}
