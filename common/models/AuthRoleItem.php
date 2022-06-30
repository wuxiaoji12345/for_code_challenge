<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "swim_auth_role_item".
 *
 * @property int $id id
 * @property int $role_id
 * @property int $auth_item_id
 * @property int $status 1-有效；2-无效
 * @property int $create_time 创建时间
 * @property string|null $update_time 更新时间
 * @property string|null $actions 方法集合
 */
class AuthRoleItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'swim_auth_role_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['role_id', 'auth_item_id', 'status', 'create_time'], 'integer'],
            [['create_time'], 'required'],
            [['update_time'], 'safe'],
            [['actions'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'id',
            'role_id' => 'Role ID',
            'auth_item_id' => 'Auth Item ID',
            'status' => '1-有效；2-无效',
            'create_time' => '创建时间',
            'update_time' => '更新时间',
            'actions' => '方法集合',
        ];
    }

    public function getRoleAuthItemIDs($roleID)
    {
        $data = $this->find()->asArray()
            ->select(['auth_item_id'])
            ->where([
                'role_id' => $roleID,
                'status' => 1,
            ])
            ->all();
        return array_column($data, 'auth_item_id');
    }

    public function refreshRoleItems($roleID, array $itemIDs)
    {
        $validIDs = [];
        $oldValidIDs = $this->oldValidIDs($roleID);
        foreach ($itemIDs as $itemID) {
            $validIDs[]= $this->saveOne($roleID, $itemID);
        }
        $diffIDs = array_diff($oldValidIDs, $validIDs);
        if (!empty($diffIDs)) {
            self::updateAll(['status' => 2], ['id' => $diffIDs]);
        }
    }

    public function saveOne($roleID, $itemID)
    {
        $model = $this->findOne(['role_id' => $roleID, 'auth_item_id' => $itemID, 'status' => 1]);
        if (!isset($model)) {
            $model = new self();
            $model->role_id = $roleID;
            $model->create_time = time();
        }
        $model->auth_item_id = $itemID;
        $model->status = 1;
        if (!$model->save()) {
            \Yii::error($model->getErrors());
            throw new \Exception('保存失败');
        }
        return $model->id;
    }

    public function oldValidIDs($roleID)
    {
        $data = $this->find()->asArray()
            ->select(['id'])
            ->where([
                'role_id' => $roleID,
                'status' => 1,
            ])
            ->all();
        return array_column($data, 'id');
    }
}
