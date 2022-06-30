<?php

namespace api\actions;

use api\controllers\Controller;
use Yii;
use common\models\Event;
use common\models\EventInfo;
use yii\base\Action;
use yii\data\ActiveDataProvider;
use yii\rest\Serializer;
use yii\web\ServerErrorHttpException;

class EventRelationIndexAction extends Action
{
    public $modelClass =   "common\models\EventRelation";
    /**
     * 过滤条件
     * @return array
     */
    protected function filter()
    {
        $urid   = Yii::$app->request->get("urid");
        $tn = $this->modelClass::tableName();
        //TODO gid 条件判断
        $filter = [
            'and',
            [$tn . '.urid' => $urid],
            [">", $tn . '.state', 0]
        ];
        return $filter;
    }

    /**
     * 字段
     */
    protected function select()
    {
        $tn = $this->modelClass::tableName();
        return [$tn . '.id', $tn . '.rgid', $tn . '.typeid', $tn . '.order_no', $tn . '.paytime', $tn . '.matchid', $tn . ".state", $tn . ".fees", $tn . ".type"];
    }
    protected function join()
    {
        $join =  [
            'registerInfo' => function ($query) {
                $tn  = EventInfo::tableName();
                $query->select([
                    $tn . '.id',
                    $tn . '.rgid',
                    $tn . '.name',
                ]);

                $query->groupBy([
                    $tn . ".rrid"
                ]);

                return $query;
            },
            'match' => function ($query) {
                $tn = Event::tableName();
                $query->select([
                    $tn . ".id",
                    $tn . ".start_time",
                    $tn . ".title",
                    $tn . ".address",
                    $tn . ".imgurl",
                ]);
                $query->andWhere(['>', $tn . ".id", 0]);
                return $query;
            }
        ];
        return $join;
    }

    public function run()
    {

        $urid   = Yii::$app->request->get("urid");
        $token   = Yii::$app->request->get("token");
        $check  =    (new Controller("api", "api"))->checkUser($urid, $token);
        if (!$check) {
            throw new ServerErrorHttpException("错误用户");
        }
        $requestParams = Yii::$app->getRequest()->getBodyParams();
        if (empty($requestParams)) {
            $requestParams = Yii::$app->getRequest()->getQueryParams();
        }

        $modelClass = $this->modelClass;
        $query = $modelClass::find();

        $query->andWhere($this->filter());
        $query->select($this->select());
        $query->joinWith($this->join());
        $data =  Yii::createObject([
            'class' => ActiveDataProvider::className(),
            'query' => $query,
            'pagination' => [
                'params' => $requestParams,
            ],
            'sort' => [
                'params' => $requestParams,
            ]
        ]);
        $data =  (new Serializer([
            'collectionEnvelope' => 'list',
            'metaEnvelope' => 'page',
        ]))->serialize($data);

        return (new Controller("api", "api"))->dataOut($data);
    }
}
