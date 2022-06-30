<?php

namespace api\models;

class UserMember extends \common\models\UserMember
{
    public function getMemberinfo(){
        return $this->hasMany(MemberInfo::class, ['id'=>'memberid']);
    }
}
