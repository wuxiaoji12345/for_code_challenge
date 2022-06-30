<?php

namespace common\models;

use common\models\base\__EventRelation;

class EventRelation extends __EventRelation
{
    const PAY_NOVALID = 0;
    const PAY_YES = 1;
    const PAY_NO = 2;
    const PAY_RETURN = 3;

    const REGISTER_CHECK_PASS = 1;//报名审核通过
    const REGISTER_CHECK_UNPASS = 2;//报名审核未通过

    public function getMatch(){
        return $this->hasOne(Event::class, ['id'=>'matchid']);
    }

    public function getInfo() {
        return $this->hasMany(EventInfo::class, ['rrid'=>'id']);
    }


    
    public function getRegisterType()
    {
        return $this->hasOne(EventType::className(), ['id' => 'typeid']);
    }

    public function getRegisterGroup()
    {
        return $this->hasOne(EventGroup::class, ['id' => 'rgid']);
    }

    public function getRegisterInfo()
    {
        return $this->hasMany(EventInfo::class, ['rgid' => 'rgid']);
    }
}
