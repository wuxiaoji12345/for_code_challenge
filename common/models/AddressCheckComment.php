<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "swim_address_check_comment".
 *
 * @property int $id
 * @property int $swim_address_check_id
 * @property int $swim_address_id
 * @property string $imgurl 图片
 * @property string $comment 文字
 * @property int $bkurid
 * @property int $is_stadium 1-场馆方 2-非场馆方
 * @property int $status 1-有效；2-删除
 * @property int|null $create_time
 * @property string|null $update_time
 */
class AddressCheckComment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'swim_address_check_comment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['swim_address_check_id', 'swim_address_id'], 'required'],
            [['swim_address_check_id', 'swim_address_id', 'bkurid', 'is_stadium', 'status', 'create_time'], 'integer'],
            [['update_time'], 'safe'],
            [['imgurl', 'comment'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'swim_address_check_id' => 'Swim Address Check ID',
            'swim_address_id' => 'Swim Address ID',
            'imgurl' => '图片',
            'comment' => '文字',
            'bkurid' => 'Bkurid',
            'is_stadium' => '1-场馆方 2-非场馆方',
            'status' => '1-有效；2-删除',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
