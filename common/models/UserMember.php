<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%user_member}}".
 *
 * @property int $id
 * @property int $urid
 * @property int $memberid
 * @property string $memberinfos
 * @property int $status 1,有效；2，删除
 * @property int $create_time
 * @property string $update_time
 */
class UserMember extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user_member}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['urid', 'memberid', 'status', 'create_time'], 'integer'],
            [['memberinfos'], 'string'],
            [['update_time'], 'safe'],
            [['urid', 'memberid'], 'unique', 'targetAttribute' => ['urid', 'memberid']],
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
            'memberid' => 'Memberid',
            'memberinfos' => 'Memberinfos',
            'status' => 'Status',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
