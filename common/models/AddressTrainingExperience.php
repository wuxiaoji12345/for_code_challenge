<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%address_training_experience}}".
 *
 * @property int $id
 * @property string $experience_id 从业人员培训记录id
 * @property string $three_personnel_id 从业人员id
 * @property string $id_card 身份证号
 * @property string $card_no 制卡卡号
 * @property int $type 角色：01-池主任；02-救生组 长；03-水质处理员；03-检查
 * @property string $learning_date 学习日期
 * @property string $learning_content 学习内容
 * @property string $results 成绩
 * @property string $address_str 服务泳馆
 * @property int $last_access 最后更新时间
 * @property int $status 1-有效；2-删除
 * @property int $create_time
 * @property string|null $update_time
 */
class AddressTrainingExperience extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%address_training_experience}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'last_access', 'status', 'create_time'], 'integer'],
            [['update_time'], 'safe'],
            [['experience_id', 'three_personnel_id'], 'string', 'max' => 32],
            [['id_card'], 'string', 'max' => 50],
            [['card_no'], 'string', 'max' => 100],
            [['learning_date', 'results'], 'string', 'max' => 10],
            [['learning_content', 'address_str'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'experience_id' => 'Experience ID',
            'three_personnel_id' => 'Three Personnel ID',
            'id_card' => 'Id Card',
            'card_no' => 'Card No',
            'type' => 'Type',
            'learning_date' => 'Learning Date',
            'learning_content' => 'Learning Content',
            'results' => 'Results',
            'address_str' => 'Address Str',
            'last_access' => 'Last Access',
            'status' => 'Status',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
