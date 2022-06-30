<?php

namespace api\models;

class ScoreGroup extends \common\models\ScoreGroup
{
    public function getEnroll() {
        return $this->hasMany(ScoreStates::class, ['gpid'=>'id']);
    }
}
