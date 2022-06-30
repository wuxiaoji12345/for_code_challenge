<?php

namespace api\models;

class MatchSessionItem extends \common\models\MatchSessionItem
{
    public function getGroup() {
        return $this->hasMany(ScoreGroup::class, ['itemid'=>'id']);
    }
}
