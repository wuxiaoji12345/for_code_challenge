<?php

namespace common\models;

use common\models\base\__EventInfo;

class EventInfo extends __EventInfo
{

    public function getRegisterRelation(){
        return $this->hasOne(EventRelation::class, ['id'=>'rrid']);
    }
}
