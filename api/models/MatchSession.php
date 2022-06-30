<?php

namespace api\models;

class MatchSession extends \common\models\MatchSession
{
    public function getItems(){
        return $this->hasMany(MatchSessionItem::class, ['ssid'=>'id']);
    }
}
