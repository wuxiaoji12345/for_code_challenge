<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "swim_auth_role".
 *
 * @property int $id id
 * @property string $name
 * @property int $status 1-有效；2-无效
 * @property int $create_time 创建时间
 * @property string|null $update_time 更新时间
 * @property int|null $gid 组织ID
 */
class AuthRole extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'swim_auth_role';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'create_time', 'gid'], 'integer'],
            [['create_time'], 'required'],
            [['update_time'], 'safe'],
            [['name'], 'string', 'max' => 64],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'id',
            'name' => 'Name',
            'status' => '1-有效；2-无效',
            'create_time' => '创建时间',
            'update_time' => '更新时间',
            'gid' => '组织ID',
        ];
    }

    public function roleList()
    {
        $data = $this->find()->asArray()
            ->select(['id', 'name'])
            ->where([
                'status' => 1,
            ])
            ->indexBy('id')
            ->all();
        return $data;
    }

    public function roleUserList($gid)
    {
        $data = $this->find()->asArray()
            ->select(['id', 'name'])
            ->where([
                'status' => 1,
                'gid' => $gid
            ])
            ->indexBy('id')
            ->all();
        return $data;
    }

    public function roleNames($roleIDs)
    {
        $data = $this->find()->asArray()
            ->select(['name'])
            ->where([
                'id' => $roleIDs,
                'status' => 1,
            ])
            ->all();
        return array_column($data, 'name');
    }

    public function roles($roleIDs)
    {
        $data = $this->find()->asArray()
            ->select(['id','name'])
            ->where([
                'id' => $roleIDs,
                'status' => 1,
            ])
            ->all();
        return array_values($data);
    }
}
