<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "swim_address_user_comment".
 *
 * @property integer $id
 * @property integer $swim_address_id
 * @property string $comment_date
 * @property integer $user_id
 * @property integer $score
 * @property string $comment
 * @property integer $status
 * @property integer $create_time
 * @property string $update_time
 */
class AddressUserComment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%address_user_comment}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['swim_address_id', 'comment_date', 'user_id'], 'required'],
            [['swim_address_id', 'user_id', 'score', 'status', 'create_time'], 'integer'],
            [['comment_date', 'update_time'], 'safe'],
            [['comment'], 'string', 'max' => 256],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'swim_address_id' => '场馆ID',
            'comment_date' => '评论时间',
            'user_id' => '用户ID',
            'score' => '分数',
            'comment' => '评价内容',
            'status' => '状态',
            'create_time' => '创建时间',
            'update_time' => '更新时间',
        ];
    }
}
