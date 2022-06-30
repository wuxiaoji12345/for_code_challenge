<?php

namespace api\models;

class ScoreEnroll extends \common\models\ScoreEnroll
{
    public function getSession() {
        return $this->hasOne(MatchSession::class, ['id'=>'ssid']);
    }

    public function getSessionitem() {
        return $this->hasOne(MatchSessionItem::class, ['id'=>'itemid']);
    }

    public function getScorestate() {
        return $this->hasOne(ScoreStates::class, ['enrollid'=>'id']);
    }
}
