<?php

namespace api\models;

class RegisterInfo extends \common\models\RegisterInfo
{
    public function getRegisterRelation(){
        return $this->hasOne(RegisterRelation::class, ['id'=>'rrid']);
    }
}
