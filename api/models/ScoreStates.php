<?php

namespace api\models;

class ScoreStates extends \common\models\ScoreStates
{
    public function getEnroll() {
        return $this->hasMany(ScoreEnroll::class, ['id'=>'enrollid']);
    }
}
