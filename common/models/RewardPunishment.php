<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "swim_reward_punishment".
 *
 * @property int $id
 * @property int $relation_id 关联id
 * @property string $name 姓名
 * @property string $content 奖惩原因即内容
 * @property string $title 奖惩称号
 * @property string $address_name 游泳馆商户名
 * @property int $create_time
 * @property string|null $update_time
 */
class RewardPunishment extends \common\models\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'swim_reward_punishment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['relation_id', 'create_time'], 'integer'],
            [['update_time'], 'safe'],
            [['name', 'content', 'title', 'address_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'relation_id' => '关联id',
            'name' => '姓名',
            'content' => '奖惩原因即内容',
            'title' => '奖惩称号',
            'address_name' => '游泳馆商户名',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
