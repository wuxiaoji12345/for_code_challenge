<?php


namespace backend\models;


class UserChannel extends \common\models\UserChannel
{
    public function getUserInfo()
    {
        return $this->hasOne(UserInfo::className(), ['urid' => 'urid']);
    }
}