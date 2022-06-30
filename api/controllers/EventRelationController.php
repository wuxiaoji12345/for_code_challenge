<?php

/**
 * Created by wayne.
 * Date: 2019/4/11
 * Time: 3:23 PM
 */

namespace api\controllers;

class EventRelationController extends Controller
{
    public $modelClass =   "common\models\EventRelation";
    public function actions()
    {
        return [
            'index' => [
                'class' => 'api\actions\EventRelationIndexAction',
                'modelClass' => $this->modelClass,
            ]
        ];
    }
}
