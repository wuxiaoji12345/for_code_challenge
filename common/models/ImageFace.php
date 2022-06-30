<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%image_face}}".
 *
 * @property int $id
 * @property int $catid 属于人脸分类的id
 * @property int $matchid 赛事id
 * @property string $imgurl 图片url
 * @property string $imgthumburl 小图url
 * @property string $imgsize 图片size
 * @property double $face_prob 是人脸的概率
 * @property string $face_rect 人脸的位置
 * @property string $pose 人脸的朝向
 * @property int $gender 性别: 1，男性；2，女性
 * @property int $age 年龄
 * @property int $dense_fea_len 特征点的维度长度
 * @property string $dense_fea 特征值数组
 * @property double $result 与该分类基准对比分值
 * @property int $state 1-有效；2-无效
 */
class ImageFace extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%image_face}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['catid', 'matchid', 'gender', 'age', 'dense_fea_len', 'state'], 'integer'],
            [['matchid', 'age', 'dense_fea_len', 'result'], 'required'],
            [['face_prob', 'result'], 'number'],
            [['dense_fea'], 'string'],
            [['imgurl', 'imgthumburl'], 'string', 'max' => 1024],
            [['imgsize', 'face_rect'], 'string', 'max' => 255],
            [['pose'], 'string', 'max' => 64],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'catid' => 'Catid',
            'matchid' => 'Matchid',
            'imgurl' => 'Imgurl',
            'imgthumburl' => 'Imgthumburl',
            'imgsize' => 'Imgsize',
            'face_prob' => 'Face Prob',
            'face_rect' => 'Face Rect',
            'pose' => 'Pose',
            'gender' => 'Gender',
            'age' => 'Age',
            'dense_fea_len' => 'Dense Fea Len',
            'dense_fea' => 'Dense Fea',
            'result' => 'Result',
            'state' => 'State',
        ];
    }
}
