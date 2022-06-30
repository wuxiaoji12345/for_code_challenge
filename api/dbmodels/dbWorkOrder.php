<?php


namespace api\dbmodels;


use api\models\MatchImageConfig;
use common\models\WorkOrder;
use common\models\WorkOrderIndex;

class dbWorkOrder
{

    public static function create($index,$work_order)
    {
        $model = new WorkOrderIndex();
        $model->load($index, '');
        if($model->save()){
            foreach ($work_order as &$v){
                $v['index_id'] = $model->id;
            }
            WorkOrder::insertOrUpdate('',$work_order,true);
        }
    }

}