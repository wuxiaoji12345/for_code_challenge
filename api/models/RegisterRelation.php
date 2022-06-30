<?php

namespace api\models;

class RegisterRelation extends \common\models\RegisterRelation
{
    const PAY_NOVALID = 0;
    const PAY_YES = 1;
    const PAY_NO = 2;
    const PAY_RETURN = 3;

    const REGISTER_CHECK_PASS = 1;//报名审核通过
    const REGISTER_CHECK_UNPASS = 2;//报名审核未通过

    public function getMatch(){
        return $this->hasOne(Match::class, ['id'=>'matchid']);
    }

    public function getInfo() {
        return $this->hasMany(RegisterInfo::class, ['rrid'=>'id']);
    }
}
